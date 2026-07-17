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

    private const DATE_FIELDS = [
        'direct:OpeningDate',
        'direct:ClosingDate',
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

        $visibleAutoFillCount = count(array_filter($plan['autoFills'] ?? [], static function ($autoFill) {
            return empty($autoFill['isExtra']);
        }));

        $autoFillsTable = $this->customise([
            'AutoFillGroups' => $autoFillGroups,
            'AutoFillCount' => $visibleAutoFillCount,
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
                'NewValueControl' => $this->buildFieldControl('conflictNewValue_' . $index, $conflict['newValue'] ?? '', $conflict['field']),
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
        $createFieldSkipSelections = [];
        $createFieldOverrides = [];
        foreach ($plan['creates'] ?? [] as $index => $create) {
            $skipSelections[$index] = !empty($data['skipCreate_' . $index]);

            foreach (ExperienceCsvImporter::enumerateCreateFields($create) as $fieldIndex => $field) {
                if (!empty($data['skipCreateField_' . $index . '_' . $fieldIndex])) {
                    $createFieldSkipSelections[$index][$fieldIndex] = true;
                }

                $submitted = $data['createFieldValue_' . $index . '_' . $fieldIndex] ?? null;
                if ($submitted !== null) {
                    $fieldKey = $field['kind'] . ':' . $field['key'];
                    $createFieldOverrides[$index][$fieldIndex] = $this->parseSubmittedValue($submitted, $fieldKey);
                }
            }
        }

        $autoFillSkipSelections = [];
        $autoFillOverrides = [];
        foreach ($plan['autoFills'] ?? [] as $index => $autoFill) {
            $autoFillSkipSelections[$index] = !empty($data['skipAutoFill_' . $index]);

            $submitted = $data['autoFillValue_' . $index] ?? null;
            if ($submitted !== null) {
                $autoFillOverrides[$index] = $this->parseSubmittedValue($submitted, $autoFill['field']);
            }
        }

        $conflictOverrides = [];
        foreach ($plan['conflicts'] ?? [] as $index => $conflict) {
            $submitted = $data['conflictNewValue_' . $index] ?? null;
            if ($submitted !== null) {
                $conflictOverrides[$index] = $this->parseSubmittedValue($submitted, $conflict['field']);
            }
        }

        $importer = new ExperienceCsvImporter();
        $summary = $importer->apply(
            $plan,
            $resolutions,
            $defunctSelections,
            $skipSelections,
            $autoFillSkipSelections,
            $createFieldSkipSelections,
            $createFieldOverrides,
            $autoFillOverrides,
            $conflictOverrides
        );

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
            $fields = ExperienceCsvImporter::enumerateCreateFields($create);

            $fieldsList = new ArrayList();
            $extraFieldsList = new ArrayList();
            foreach ($fields as $fieldIndex => $field) {
                $fieldKey = $field['kind'] . ':' . $field['key'];
                $data = ArrayData::create([
                    'Index' => $index,
                    'FieldIndex' => $fieldIndex,
                    'FieldLabel' => $this->humanizeField($fieldKey),
                    'ValueControl' => $this->buildFieldControl('createFieldValue_' . $index . '_' . $fieldIndex, $field['value'], $fieldKey),
                    'DefaultSkip' => $this->isDefaultSkipField($fieldKey),
                    'RowId' => 'createExtraRow_' . $index . '_' . $fieldIndex,
                ]);

                if (!empty($field['isExtra'])) {
                    $extraFieldsList->push($data);
                } else {
                    $fieldsList->push($data);
                }
            }

            $list->push(ArrayData::create([
                'Index' => $index,
                'Title' => $create['title'],
                'FieldCount' => $fieldsList->count(),
                'Fields' => $fieldsList,
                'ExtraFields' => $extraFieldsList,
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
                    'ExtraFields' => new ArrayList(),
                ]);
                $groups->push($currentGroup);
            }

            $data = ArrayData::create([
                'Index' => $index,
                'FieldLabel' => $this->humanizeField($autoFill['field']),
                'ValueControl' => $this->buildFieldControl('autoFillValue_' . $index, $autoFill['newValue'] ?? '', $autoFill['field']),
                'DefaultSkip' => $this->isDefaultSkipField($autoFill['field']),
                'RowId' => 'autoFillExtraRow_' . $index,
            ]);

            if (!empty($autoFill['isExtra'])) {
                $currentGroup->ExtraFields->push($data);
            } else {
                $currentGroup->Fields->push($data);
            }
        }

        return $groups;
    }

    private function humanizeField(string $field): string
    {
        [$kind, $key] = explode(':', $field, 2) + [null, null];

        if ($kind === 'data') {
            return $key;
        }
        if ($key === 'TypeTitle') {
            return 'Type';
        }
        if ($key === 'AreaTitle') {
            return 'Area';
        }

        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $key));
    }

    /**
     * Read-only display value (currently only used for a conflict's
     * "existing value" column, which is never editable - editable values go
     * through buildFieldControl() instead). Line breaks are kept as real
     * newlines (rendered via CSS white-space: pre-line in the template) so
     * multi-line values like the min-size/min-age fields display the same
     * way they will once saved, instead of being squashed onto one line.
     */
    private function formatDisplayValue($value, string $field): string
    {
        if (in_array($field, self::BOOLEAN_FIELDS, true)) {
            return $value ? 'Yes' : 'No';
        }
        if (in_array($field, self::DATE_FIELDS, true)) {
            return $this->formatNiceDate((string) $value);
        }

        return $this->htmlValueToPlainText((string) $value);
    }

    /**
     * Converts a stored value (HTML for "data:" fields, e.g.
     * "<p>132 cm<br>Under 132 cm only when accompanied by an adult.</p>",
     * plain text otherwise) into readable plain text with real line breaks,
     * for read-only display or as the starting content of an edit textarea.
     */
    private function htmlValueToPlainText(string $html): string
    {
        $text = str_replace(['<br>', '<br/>', '<br />'], "\n", $html);
        return trim(strip_tags($text));
    }

    /**
     * Inverse of htmlValueToPlainText() for "data:" fields - turns
     * staff-edited plain text (one line per <br>-separated part) back into
     * the "<p>...</p>" shape ExperienceData::Description is stored in.
     */
    private function plainTextToHtmlValue(string $text): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $lines = array_filter(array_map('trim', $lines), static function ($line) {
            return $line !== '';
        });
        return '<p>' . implode('<br>', $lines) . '</p>';
    }

    /**
     * Builds the HTML for the editable form control shown for a single
     * import field value, so staff can correct the CSV value before
     * confirming the import - every value written by the importer (create,
     * auto-fill, or a conflict's "new value") goes through here rather than
     * being shown as static text.
     */
    private function buildFieldControl(string $name, $value, string $field): string
    {
        $escapedName = htmlspecialchars($name, ENT_QUOTES);

        if (in_array($field, self::BOOLEAN_FIELDS, true)) {
            $isYes = (bool) $value;
            return sprintf(
                '<select name="%s" class="import_edit_select">'
                . '<option value="1"%s>Yes</option>'
                . '<option value="0"%s>No</option>'
                . '</select>',
                $escapedName,
                $isYes ? ' selected' : '',
                $isYes ? '' : ' selected'
            );
        }

        if (in_array($field, self::DATE_FIELDS, true)) {
            // Same native HTML5 date input SilverStripe's own DateField uses
            // for OpeningDate/ClosingDate elsewhere in the CMS (see
            // Experience::getCMSFields()) - value stays ISO (yyyy-mm-dd), the
            // browser handles displaying/formatting it per locale.
            return sprintf(
                '<input type="date" name="%s" value="%s" class="import_edit_input">',
                $escapedName,
                htmlspecialchars((string) $value, ENT_QUOTES)
            );
        }

        if ($field === 'direct:State') {
            $options = '';
            foreach (ExperienceCsvImporter::ALLOWED_STATES as $state) {
                $options .= sprintf(
                    '<option value="%s"%s>%s</option>',
                    htmlspecialchars($state, ENT_QUOTES),
                    $state === $value ? ' selected' : '',
                    htmlspecialchars($state)
                );
            }
            return sprintf('<select name="%s" class="import_edit_select">%s</select>', $escapedName, $options);
        }

        [$kind] = explode(':', $field, 2) + [null, null];

        if ($kind === 'data' || $field === 'direct:Description') {
            $editable = $kind === 'data' ? $this->htmlValueToPlainText((string) $value) : (string) $value;
            return sprintf(
                '<textarea name="%s" class="import_edit_textarea" rows="1">%s</textarea>',
                $escapedName,
                htmlspecialchars($editable)
            );
        }

        return sprintf(
            '<input type="text" name="%s" value="%s" class="import_edit_input">',
            $escapedName,
            htmlspecialchars((string) $value, ENT_QUOTES)
        );
    }

    /**
     * Converts a raw submitted form value back into the same shape the
     * importer's parsed plan uses for that field, so it can be passed to
     * ExperienceCsvImporter::apply() as an override.
     */
    private function parseSubmittedValue(string $submitted, string $field): string
    {
        if (in_array($field, self::BOOLEAN_FIELDS, true)) {
            return $submitted === '1' ? '1' : '0';
        }

        if (in_array($field, self::DATE_FIELDS, true)) {
            return ExperienceCsvImporter::parseDate(trim($submitted)) ?? '';
        }

        [$kind] = explode(':', $field, 2) + [null, null];
        if ($kind === 'data') {
            return $this->plainTextToHtmlValue($submitted);
        }

        return trim($submitted);
    }

    /**
     * The "internal_notes" CSV column maps to the "Internal Notes"
     * ExperienceDataType, which staff want left out of the database by
     * default - so its Skip checkbox starts pre-checked in the review UI,
     * unlike every other field.
     */
    private function isDefaultSkipField(string $field): bool
    {
        return $field === 'data:' . ExperienceCsvImporter::INTERNAL_NOTES_TYPE_TITLE;
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
