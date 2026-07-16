<?php

namespace App\Import;

use PageController;
use App\ExperienceDatabase\ExperienceLocation;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FileField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\Security;
use SilverStripe\View\ArrayData;

/**
 * Frontend controller for ExperienceImportPage. Access is gated entirely via
 * ExperienceImportPage::canView() (staff only), which SilverStripe enforces
 * for every action on this controller, so no additional permission checks
 * are needed here.
 *
 * @property \App\Import\ExperienceImportPage $dataRecord
 * @method \App\Import\ExperienceImportPage data()
 * @mixin \App\Import\ExperienceImportPage
 */
class ExperienceImportPageController extends PageController
{
    private static $allowed_actions = [
        'UploadForm',
        'processUpload',
        'review',
        'ReviewForm',
        'confirmImport',
        'done',
    ];

    private const BOOLEAN_FIELDS = [
        'direct:HasSingleRider',
        'direct:HasFastpass',
        'direct:HasOnridePhoto',
        'direct:AccessibleToHandicapped',
    ];

    public function UploadForm()
    {
        $csvField = new FileField('CsvFile', 'CSV File');
        $csvField->getValidator()->setAllowedExtensions(['csv']);

        $fields = new FieldList(
            (new DropdownField('LocationID', 'Place', ExperienceLocation::get()->map('ID', 'Title')))
                ->setEmptyString('- Select a place -'),
            $csvField
        );

        $actions = new FieldList(
            new FormAction('processUpload', 'Start import')
        );

        $validator = new RequiredFields(['LocationID', 'CsvFile']);

        return new Form($this, 'UploadForm', $fields, $actions, $validator);
    }

    public function processUpload($data, Form $form)
    {
        $location = ExperienceLocation::get()->byID((int) ($data['LocationID'] ?? 0));
        if (!$location) {
            $form->sessionMessage('Please select a place.', 'bad');
            return $this->redirectBack();
        }

        $fileData = $data['CsvFile'] ?? null;
        if (empty($fileData['tmp_name']) || !is_uploaded_file($fileData['tmp_name'])) {
            $form->sessionMessage('Please upload a CSV file.', 'bad');
            return $this->redirectBack();
        }

        $importer = new ExperienceCsvImporter();
        $plan = $importer->parseAndDiff($fileData['tmp_name'], $location);

        $import = ExperienceCsvImport::create();
        $import->OriginalFilename = $fileData['name'];
        $import->PlanJSON = json_encode($plan);
        $import->Status = 'Pending';
        $import->LocationID = $location->ID;
        $currentMember = Security::getCurrentUser();
        if ($currentMember) {
            $import->SubmittedByID = $currentMember->ID;
        }
        $import->write();

        return $this->redirect($this->Link('review/' . $import->ID));
    }

    public function review()
    {
        $import = $this->getPendingImport($this->resolveImportId());
        if (!$import) {
            return $this->httpError(404);
        }

        $plan = json_decode($import->PlanJSON, true) ?: [];

        return [
            'Import' => $import,
        ];
    }

    public function ReviewForm()
    {
        $import = $this->getPendingImport($this->resolveImportId());
        if (!$import) {
            return null;
        }

        $plan = json_decode($import->PlanJSON, true) ?: [];
        $createsList = $this->buildCreatesList($plan['creates'] ?? []);
        $autoFillGroups = $this->buildAutoFillGroups($plan['autoFills'] ?? []);
        $missingItems = $this->buildMissingList($plan['missing'] ?? []);
        $conflictGroups = $this->buildConflictGroups($plan['conflicts'] ?? []);

        $createsTable = $this->customise(['Creates' => $createsList])
            ->renderWith(['type' => 'Includes', 'CreatesTable']);

        $autoFillsTable = $this->customise([
            'AutoFillGroups' => $autoFillGroups,
            'AutoFillCount' => count($plan['autoFills'] ?? []),
        ])->renderWith(['type' => 'Includes', 'AutoFillsTable']);

        $missingTable = $this->customise(['MissingItems' => $missingItems])
            ->renderWith(['type' => 'Includes', 'MissingTable']);

        $conflictsTable = $this->customise([
            'ConflictGroups' => $conflictGroups,
            'ConflictCount' => count($plan['conflicts'] ?? []),
        ])->renderWith(['type' => 'Includes', 'ConflictsTable']);

        $fields = new FieldList(
            new HiddenField('ImportID', '', $import->ID),
            new LiteralField('CreatesTable', (string) $createsTable),
            new LiteralField('AutoFillsTable', (string) $autoFillsTable),
            new LiteralField('MissingTable', (string) $missingTable),
            new LiteralField('ConflictsTable', (string) $conflictsTable)
        );

        $actions = new FieldList(
            new FormAction('confirmImport', 'Confirm import')
        );

        return new Form($this, 'ReviewForm', $fields, $actions);
    }

    /**
     * Experiences that exist for this place but weren't matched by any row
     * in the uploaded file (produced by ExperienceCsvImporter::parseAndDiff()).
     */
    private function buildMissingList(array $missing): ArrayList
    {
        $list = new ArrayList();
        foreach ($missing as $index => $item) {
            $list->push(ArrayData::create([
                'Index' => $index,
                'Title' => $item['title'],
                'State' => $item['state'],
            ]));
        }
        return $list;
    }

    /**
     * Groups the flat conflicts list (in the order produced by
     * ExperienceCsvImporter, which processes one attraction fully before
     * moving to the next) into one entry per attraction, each carrying its
     * fields - so the attraction title can be rendered once as a heading
     * instead of being repeated for every conflicting field.
     */
    private function buildConflictGroups(array $conflicts): ArrayList
    {
        $groups = new ArrayList();
        $currentGroup = null;
        $currentTitle = null;

        foreach ($conflicts as $index => $conflict) {
            if ($currentGroup === null || $conflict['title'] !== $currentTitle) {
                $currentTitle = $conflict['title'];
                $currentGroup = ArrayData::create([
                    'Title' => $currentTitle,
                    'Fields' => new ArrayList(),
                ]);
                $groups->push($currentGroup);
            }

            $currentGroup->Fields->push(ArrayData::create([
                'Index' => $index,
                'FieldLabel' => $this->humanizeField($conflict['field']),
                'OldValue' => $this->formatDisplayValue($conflict['oldValue'] ?? '', $conflict['field']),
                'NewValue' => $this->formatDisplayValue($conflict['newValue'] ?? '', $conflict['field']),
            ]));
        }

        return $groups;
    }

    public function confirmImport($data, Form $form)
    {
        $import = $this->getPendingImport((int) ($data['ImportID'] ?? 0));
        if (!$import) {
            return $this->httpError(404);
        }

        $plan = json_decode($import->PlanJSON, true) ?: [];

        $resolutions = [];
        foreach ($plan['conflicts'] ?? [] as $index => $conflict) {
            $resolutions[$index] = ($data['resolution_' . $index] ?? 'old') === 'new' ? 'new' : 'old';
        }

        $defunctSelections = [];
        foreach ($plan['missing'] ?? [] as $index => $missing) {
            $defunctSelections[$index] = !empty($data['markDefunct_' . $index]);
        }

        $skipSelections = [];
        foreach ($plan['creates'] ?? [] as $index => $create) {
            $skipSelections[$index] = !empty($data['skipCreate_' . $index]);
        }

        $autoFillSkipSelections = [];
        foreach ($plan['autoFills'] ?? [] as $index => $autoFill) {
            $autoFillSkipSelections[$index] = !empty($data['skipAutoFill_' . $index]);
        }

        $importer = new ExperienceCsvImporter();
        $summary = $importer->apply($plan, $resolutions, $defunctSelections, $skipSelections, $autoFillSkipSelections);

        // Redirect (rather than returning the template data directly) so the
        // response is rendered under the "done" action/template - a POST to
        // ReviewForm would otherwise resolve to the ReviewForm/index template.
        $summary['locationTitle'] = $import->Location()->Title;
        $this->getRequest()->getSession()->set('ExperienceImportSummary', $summary);

        // The staging record was only needed to carry the parsed plan between
        // the review and confirm steps; completed imports are deleted right
        // away instead of being kept around indefinitely.
        $import->delete();

        return $this->redirect($this->Link('done'));
    }

    public function done()
    {
        $session = $this->getRequest()->getSession();
        $summary = $session->get('ExperienceImportSummary');
        $session->clear('ExperienceImportSummary');

        if (!$summary) {
            return $this->redirect($this->Link());
        }

        return [
            'Summary' => ArrayData::create($summary),
        ];
    }

    private function getPendingImport(int $id): ?ExperienceCsvImport
    {
        $import = ExperienceCsvImport::get()->byID($id);
        if (!$import || $import->Status !== 'Pending') {
            return null;
        }
        return $import;
    }

    /**
     * ReviewForm() is called both when rendering review/<ID> (ID present as a
     * URL param) and when SilverStripe rebuilds the form to process its own
     * POST submission to .../ReviewForm (no URL param, so the hidden
     * ImportID field submitted with the form is used instead).
     */
    private function resolveImportId(): int
    {
        $fromUrl = $this->getRequest()->param('ID');
        if ($fromUrl) {
            return (int) $fromUrl;
        }
        return (int) $this->getRequest()->requestVar('ImportID');
    }

    private function buildCreatesList(array $creates): ArrayList
    {
        $list = new ArrayList();
        foreach ($creates as $index => $create) {
            $fieldCount = max(0, count($create['directFields'] ?? []) - 1) + count($create['dataFields'] ?? []);
            $list->push(ArrayData::create([
                'Index' => $index,
                'Title' => $create['title'],
                'FieldCount' => $fieldCount,
                'TrainCount' => $create['trainCount'] ?? 0,
            ]));
        }
        return $list;
    }

    /**
     * Groups the flat auto-fills list (in the order produced by
     * ExperienceCsvImporter, which processes one attraction fully before
     * moving to the next) into one entry per attraction, so the title can be
     * shown once as a heading instead of being repeated for every field.
     */
    private function buildAutoFillGroups(array $autoFills): ArrayList
    {
        $groups = new ArrayList();
        $currentGroup = null;
        $currentTitle = null;

        foreach ($autoFills as $index => $autoFill) {
            if ($currentGroup === null || $autoFill['title'] !== $currentTitle) {
                $currentTitle = $autoFill['title'];
                $currentGroup = ArrayData::create([
                    'Title' => $currentTitle,
                    'Fields' => new ArrayList(),
                ]);
                $groups->push($currentGroup);
            }

            $currentGroup->Fields->push(ArrayData::create([
                'Index' => $index,
                'FieldLabel' => $this->humanizeField($autoFill['field']),
                'NewValue' => $this->formatDisplayValue($autoFill['newValue'] ?? '', $autoFill['field']),
            ]));
        }

        return $groups;
    }

    private function humanizeField(string $field): string
    {
        if ($field === 'trains') {
            return 'Trains';
        }

        [$kind, $key] = explode(':', $field, 2) + [null, null];

        if ($kind === 'data') {
            return $key;
        }
        if ($key === 'TypeTitle') {
            return 'Type';
        }

        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $key));
    }

    private function formatDisplayValue($value, string $field): string
    {
        if ($field === 'trains') {
            return $value . ' Trains';
        }
        if (in_array($field, self::BOOLEAN_FIELDS, true)) {
            return $value ? 'Yes' : 'No';
        }
        if (in_array($field, ['direct:OpeningDate', 'direct:ClosingDate'], true)) {
            return $this->formatNiceDate((string) $value);
        }

        $text = str_replace(['<br>', '<br/>', '<br />'], ' | ', (string) $value);
        return trim(strip_tags($text));
    }

    /**
     * Formats a stored ISO date (e.g. "2001-04-13") as dd.mm.yyyy for
     * display. The underlying plan value stays ISO so it can still be
     * written straight into the Date DB field.
     */
    private function formatNiceDate(string $isoDate): string
    {
        if ($isoDate === '') {
            return '';
        }
        $timestamp = strtotime($isoDate);
        if ($timestamp === false) {
            return $isoDate;
        }
        return date('d.m.Y', $timestamp);
    }
}
