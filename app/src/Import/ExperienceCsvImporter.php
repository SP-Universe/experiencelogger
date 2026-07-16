<?php

namespace App\Import;

use App\ExperienceDatabase\Experience;
use App\ExperienceDatabase\ExperienceData;
use App\ExperienceDatabase\ExperienceDataType;
use App\ExperienceDatabase\ExperienceLocation;
use App\ExperienceDatabase\ExperienceTrain;
use App\ExperienceDatabase\ExperienceType;
use League\Csv\Reader;

/**
 * Parses a "heide-park.csv"-style attraction export, diffs it against the
 * Experiences already stored for a given ExperienceLocation and produces a
 * plan that either creates missing Experiences, auto-fills empty fields, or
 * flags real conflicts for manual before/after review.
 */
class ExperienceCsvImporter
{
    private const DIRECT_TEXT_FIELDS = [
        'Coordinates' => 'Coordinates',
        'Beschreibung' => 'Description',
        'Experience-Link' => 'ExperienceLink',
        'Fastpass-Link' => 'FastpassLink',
    ];

    private const DIRECT_BOOL_FIELDS = [
        'Single-Rider' => 'HasSingleRider',
        'Fastpass' => 'HasFastpass',
        'Onridephoto' => 'HasOnridePhoto',
        'Handicapped' => 'AccessibleToHandicapped',
    ];

    private const DIRECT_DATE_FIELDS = [
        'Opening-Date' => 'OpeningDate',
        'Closing-Date' => 'ClosingDate',
    ];

    private const ALLOWED_STATES = ['Active', 'In Maintenance', 'InActive', 'Coming Soon', 'Other', 'Defunct'];

    // CSV column => [ExperienceDataType title, unit suffix or null], value used as-is
    private const SIMPLE_DATA_FIELDS = [
        'Height' => ['Height', 'm'],
        'Speed' => ['Speed', 'km/h'],
        'Capacity-Ride' => ['Capacity per ride', 'people'],
        'Diameter' => ['Diameter', null],
        'Incident' => ['Incident', null],
        'Inversions' => ['Inversions', null],
        'Manufactor' => ['Manufacturer', null],
        'Gradient' => ['Max Gradient', 'degrees'],
        'MaxAge' => ['Maximum age', 'years'],
        'MaxSize' => ['Maximum body size', 'cm'],
        'MinWeight' => ['Minimum weight', 'kg'],
        'MaxWeight' => ['Maximum weight', 'kg'],
        'Other' => ['Other', null],
        'PreviousName' => ['Previous Names', null],
        'Price' => ['Price', null],
        'Sponsor' => ['Sponsor', null],
        'Actor' => ['Actor', null],
        'Type' => ['Type', null],
    ];

    // CSV column => [ExperienceDataType title, unit suffix], value formatted with a thousands separator
    private const THOUSANDS_DATA_FIELDS = [
        'Length' => ['Track length', 'm'],
        'Capacity-Hour' => ['Capacity per hour', 'pph'],
    ];

    // CSV column => ExperienceDataType title. Written in addition to the
    // matching entry in DIRECT_DATE_FIELDS, using the CSV's own value as-is
    // (already human-readable, e.g. "13.04.2001"), so the date ends up both
    // on the Experience's date field and as a "More Data" entry.
    private const DATE_DATA_FIELDS = [
        'Opening-Date' => 'Opening',
        'Closing-Date' => 'Closing',
    ];

    public function parseAndDiff(string $csvPath, ExperienceLocation $location): array
    {
        $reader = Reader::createFromPath($csvPath, 'r');
        $reader->setDelimiter(';');
        $reader->setHeaderOffset(0);

        $existingExperiences = iterator_to_array(Experience::get()->filter('ParentID', $location->ID));

        $plan = [
            'locationId' => $location->ID,
            'creates' => [],
            'autoFills' => [],
            'conflicts' => [],
            'missing' => [],
        ];

        $matchedIds = [];

        foreach ($reader->getRecords() as $row) {
            $title = trim($row['Title'] ?? '');
            if ($title === '') {
                continue;
            }

            $existing = $this->findExisting($existingExperiences, $title);

            if (!$existing) {
                $plan['creates'][] = $this->buildCreate($row, $title);
                continue;
            }

            $matchedIds[] = $existing->ID;
            $this->diffExisting($row, $existing, $plan);
        }

        // Experiences that already exist for this place but weren't present
        // in the uploaded file - already-Defunct ones are skipped since
        // marking them Defunct again would be a no-op.
        foreach ($existingExperiences as $experience) {
            if (in_array($experience->ID, $matchedIds, true)) {
                continue;
            }
            if ($experience->State === 'Defunct') {
                continue;
            }
            $plan['missing'][] = [
                'experienceId' => $experience->ID,
                'title' => $experience->Title,
                'state' => $experience->State,
            ];
        }

        return $plan;
    }

    /**
     * Writes a plan produced by parseAndDiff() (merged with the staff member's
     * conflict resolutions, defunct selections and create/auto-fill opt-outs)
     * into the database.
     *
     * @param array $plan
     * @param array $resolutions Conflict index (int) => 'old'|'new'
     * @param array $defunctSelections Missing index (int) => bool, whether to mark as Defunct
     * @param array $skipSelections Create index (int) => bool, whether to skip creating it
     * @param array $autoFillSkipSelections AutoFill index (int) => bool, whether to skip applying it
     * @param array $createFieldSkipSelections Create index (int) => [field index (int) => bool], individual fields to leave out of an otherwise-created attraction
     */
    public function apply(
        array $plan,
        array $resolutions,
        array $defunctSelections = [],
        array $skipSelections = [],
        array $autoFillSkipSelections = [],
        array $createFieldSkipSelections = []
    ): array {
        $summary = ['created' => 0, 'skipped' => 0, 'autoFilled' => 0, 'skippedAutoFills' => 0, 'applied' => 0, 'kept' => 0, 'markedDefunct' => 0];

        foreach ($plan['creates'] as $index => $create) {
            if (!empty($skipSelections[$index])) {
                $summary['skipped']++;
                continue;
            }
            $this->applyCreate($create, $plan['locationId'], $createFieldSkipSelections[$index] ?? []);
            $summary['created']++;
        }

        foreach ($plan['autoFills'] as $index => $autoFill) {
            if (!empty($autoFillSkipSelections[$index])) {
                $summary['skippedAutoFills']++;
                continue;
            }
            $this->applyFieldChange($autoFill);
            $summary['autoFilled']++;
        }

        foreach ($plan['conflicts'] as $index => $conflict) {
            $resolution = $resolutions[$index] ?? 'old';
            if ($resolution === 'new') {
                $this->applyFieldChange($conflict);
                $summary['applied']++;
            } else {
                $summary['kept']++;
            }
        }

        foreach ($plan['missing'] ?? [] as $index => $missing) {
            if (empty($defunctSelections[$index])) {
                continue;
            }
            $experience = Experience::get()->byID($missing['experienceId']);
            if ($experience) {
                $experience->State = 'Defunct';
                $experience->write();
                $summary['markedDefunct']++;
            }
        }

        return $summary;
    }

    /**
     * Flat, index-stable list of the individual fields a "will be created"
     * plan entry would write (Title excluded - it's never skippable). Used
     * both to render the per-field skip checkboxes in the review UI and to
     * apply per-field skip selections when writing, so the indices always
     * line up between the two.
     */
    public static function enumerateCreateFields(array $create): array
    {
        $fields = [];

        foreach ($create['directFields'] ?? [] as $key => $value) {
            if ($key === 'Title') {
                continue;
            }
            $fields[] = ['kind' => 'direct', 'key' => $key, 'value' => $value];
        }

        foreach ($create['dataFields'] ?? [] as $dataField) {
            $fields[] = ['kind' => 'data', 'key' => $dataField['typeTitle'], 'value' => $dataField['value']];
        }

        return $fields;
    }

    private function findExisting(array $experiences, string $title): ?Experience
    {
        $needle = $this->normalizeTitle($title);
        foreach ($experiences as $experience) {
            if ($this->normalizeTitle($experience->Title) === $needle) {
                return $experience;
            }
        }

        // Fall back to a looser match that also ignores spacing/punctuation
        // (hyphens, apostrophes, etc.), so near-identical titles like
        // "Indy-Blitz" vs "Indy Blitz" or "Frau Mümmel's Kiosk" vs
        // "Frau Mümmels Kiosk" are still recognised as the same attraction
        // instead of creating a duplicate. The title difference is then
        // surfaced as a normal conflict for manual review (see
        // diffExisting()). Only matches if exactly one candidate shares the
        // fuzzy key - an ambiguous match is treated as not found.
        $fuzzyNeedle = $this->fuzzyTitleKey($title);
        $candidates = array_filter($experiences, function ($experience) use ($fuzzyNeedle) {
            return $this->fuzzyTitleKey($experience->Title) === $fuzzyNeedle;
        });

        return count($candidates) === 1 ? reset($candidates) : null;
    }

    private function normalizeTitle(string $title): string
    {
        $title = str_replace(["\xe2\x80\x93", "\xe2\x80\x94"], '-', $title); // en dash / em dash -> hyphen
        $title = preg_replace('/\s+/', ' ', $title);
        return strtolower(trim($title));
    }

    private function fuzzyTitleKey(string $title): string
    {
        $title = str_replace(["\xe2\x80\x93", "\xe2\x80\x94"], '-', $title);
        $title = mb_strtolower(trim($title), 'UTF-8');
        return preg_replace('/[^\p{L}\p{N}]+/u', '', $title);
    }

    private function buildCreate(array $row, string $title): array
    {
        $directFields = ['Title' => $title];

        foreach (self::DIRECT_TEXT_FIELDS as $csvColumn => $dbField) {
            $value = trim($row[$csvColumn] ?? '');
            if ($value !== '') {
                $directFields[$dbField] = $value;
            }
        }

        foreach (self::DIRECT_BOOL_FIELDS as $csvColumn => $dbField) {
            $value = trim($row[$csvColumn] ?? '');
            if ($value !== '') {
                $directFields[$dbField] = (bool) ((int) $value);
            }
        }

        foreach (self::DIRECT_DATE_FIELDS as $csvColumn => $dbField) {
            $date = $this->parseDate(trim($row[$csvColumn] ?? ''));
            if ($date !== null) {
                $directFields[$dbField] = $date;
            }
        }

        $state = trim($row['Status'] ?? '');
        if (in_array($state, self::ALLOWED_STATES, true)) {
            $directFields['State'] = $state;
        }

        $xplType = trim($row['XPL-Type'] ?? '');
        if ($xplType !== '') {
            $directFields['TypeTitle'] = $xplType;
        }

        return [
            'title' => $title,
            'directFields' => $directFields,
            'dataFields' => $this->buildDataFields($row),
            'trainCount' => $this->parseInt($row['Trains'] ?? ''),
        ];
    }

    private function diffExisting(array $row, Experience $existing, array &$plan): void
    {
        // Title itself (only differs from the existing title when matched via
        // the fuzzy fallback in findExisting(), e.g. "Indy-Blitz" vs "Indy Blitz")
        $this->diffDirectField($existing, 'Title', trim($row['Title'] ?? ''), $plan);

        // Direct text/date fields
        foreach (self::DIRECT_TEXT_FIELDS as $csvColumn => $dbField) {
            $this->diffDirectField($existing, $dbField, trim($row[$csvColumn] ?? ''), $plan);
        }
        foreach (self::DIRECT_DATE_FIELDS as $csvColumn => $dbField) {
            $date = $this->parseDate(trim($row[$csvColumn] ?? ''));
            if ($date !== null) {
                $this->diffDirectField($existing, $dbField, $date, $plan);
            }
        }

        // Direct boolean fields - always compared, never "auto-filled" (0 is a real value, not "empty")
        foreach (self::DIRECT_BOOL_FIELDS as $csvColumn => $dbField) {
            $raw = trim($row[$csvColumn] ?? '');
            if ($raw === '') {
                continue;
            }
            $newValue = (int) ((bool) ((int) $raw));
            $oldValue = (int) ((bool) $existing->$dbField);
            if ($newValue !== $oldValue) {
                $plan['conflicts'][] = [
                    'experienceId' => $existing->ID,
                    'title' => $existing->Title,
                    'field' => 'direct:' . $dbField,
                    'oldValue' => $oldValue,
                    'newValue' => $newValue,
                    'isBoolean' => true,
                ];
            }
        }

        $state = trim($row['Status'] ?? '');
        if (in_array($state, self::ALLOWED_STATES, true)) {
            $this->diffDirectField($existing, 'State', $state, $plan);
        }

        $xplType = trim($row['XPL-Type'] ?? '');
        if ($xplType !== '') {
            $currentType = $existing->Type()->exists() ? $existing->Type()->Title : '';
            if ($this->normalizeText($currentType) === '') {
                $plan['autoFills'][] = [
                    'experienceId' => $existing->ID,
                    'title' => $existing->Title,
                    'field' => 'direct:TypeTitle',
                    'newValue' => $xplType,
                ];
            } elseif ($this->normalizeText($currentType) !== $this->normalizeText($xplType)) {
                $plan['conflicts'][] = [
                    'experienceId' => $existing->ID,
                    'title' => $existing->Title,
                    'field' => 'direct:TypeTitle',
                    'oldValue' => $currentType,
                    'newValue' => $xplType,
                ];
            }
        }

        // ExperienceData-backed fields
        foreach ($this->buildDataFields($row) as $dataField) {
            $this->diffDataField($existing, $dataField['typeTitle'], $dataField['value'], $plan);
        }

        // Trains (additive only, never a conflict)
        $wantedTrains = $this->parseInt($row['Trains'] ?? '');
        if ($wantedTrains !== null) {
            $currentTrains = $existing->ExperienceTrains()->count();
            if ($wantedTrains > $currentTrains) {
                $plan['autoFills'][] = [
                    'experienceId' => $existing->ID,
                    'title' => $existing->Title,
                    'field' => 'trains',
                    'newValue' => $wantedTrains,
                ];
            }
        }
    }

    private function diffDirectField(Experience $existing, string $dbField, string $newValue, array &$plan): void
    {
        if ($newValue === '') {
            return;
        }
        $oldValue = (string) $existing->$dbField;
        if ($this->normalizeText($oldValue) === '') {
            $plan['autoFills'][] = [
                'experienceId' => $existing->ID,
                'title' => $existing->Title,
                'field' => 'direct:' . $dbField,
                'newValue' => $newValue,
            ];
            return;
        }
        if ($this->normalizeText($oldValue) === $this->normalizeText($newValue)) {
            return;
        }
        $plan['conflicts'][] = [
            'experienceId' => $existing->ID,
            'title' => $existing->Title,
            'field' => 'direct:' . $dbField,
            'oldValue' => $oldValue,
            'newValue' => $newValue,
        ];
    }

    private function diffDataField(Experience $existing, string $typeTitle, string $newValue, array &$plan): void
    {
        $dataType = ExperienceDataType::get()->filter('Title', $typeTitle)->first();
        $existingData = $dataType
            ? $existing->ExperienceData()->filter('TypeID', $dataType->ID)->first()
            : null;

        $oldValue = $existingData ? (string) $existingData->Description : '';

        if ($this->normalizeText($oldValue) === '') {
            $plan['autoFills'][] = [
                'experienceId' => $existing->ID,
                'title' => $existing->Title,
                'field' => 'data:' . $typeTitle,
                'newValue' => $newValue,
            ];
            return;
        }
        if ($this->normalizeText($oldValue) === $this->normalizeText($newValue)) {
            return;
        }
        $plan['conflicts'][] = [
            'experienceId' => $existing->ID,
            'title' => $existing->Title,
            'field' => 'data:' . $typeTitle,
            'oldValue' => $oldValue,
            'newValue' => $newValue,
        ];
    }

    private function buildDataFields(array $row): array
    {
        $fields = [];

        foreach (self::SIMPLE_DATA_FIELDS as $csvColumn => [$typeTitle, $unit]) {
            $value = trim($row[$csvColumn] ?? '');
            if ($value === '') {
                continue;
            }
            $fields[] = [
                'typeTitle' => $typeTitle,
                'value' => $this->wrapHtml($unit ? "{$value} {$unit}" : $value),
            ];
        }

        foreach (self::THOUSANDS_DATA_FIELDS as $csvColumn => [$typeTitle, $unit]) {
            $value = trim($row[$csvColumn] ?? '');
            if ($value === '') {
                continue;
            }
            $fields[] = [
                'typeTitle' => $typeTitle,
                'value' => $this->wrapHtml($this->formatThousands($value) . " {$unit}"),
            ];
        }

        $duration = trim($row['Duration'] ?? '');
        if ($duration !== '') {
            $fields[] = ['typeTitle' => 'Ride Time', 'value' => $this->wrapHtml("{$duration} min")];
        }

        $constructionCost = trim($row['ConstructionCoast'] ?? '');
        if ($constructionCost !== '' && is_numeric($constructionCost)) {
            $millions = $this->formatMillions((float) $constructionCost);
            $fields[] = ['typeTitle' => 'Construction cost', 'value' => $this->wrapHtml("approx. {$millions} million euros")];
        }

        $minAge = trim($row['MinAge'] ?? '');
        $minAgeAlone = trim($row['MinAgeAlone'] ?? '');
        if ($minAge !== '' || $minAgeAlone !== '') {
            $parts = [];
            if ($minAge !== '') {
                $parts[] = "{$minAge} years";
            }
            if ($minAgeAlone !== '') {
                $parts[] = "Under {$minAgeAlone} years only when accompanied by an adult.";
            }
            $fields[] = ['typeTitle' => 'Minimum age', 'value' => $this->wrapHtml(implode('<br>', $parts))];
        }

        $minSize = trim($row['MinSize'] ?? '');
        $maxSizeAlone = trim($row['MaxSizeAlone'] ?? '');
        if ($minSize !== '' || $maxSizeAlone !== '') {
            $parts = [];
            if ($minSize !== '') {
                $parts[] = "{$minSize} cm";
            }
            if ($maxSizeAlone !== '') {
                $parts[] = "Under {$maxSizeAlone} cm only when accompanied by an adult.";
            }
            $fields[] = ['typeTitle' => 'Minimum body size', 'value' => $this->wrapHtml(implode('<br>', $parts))];
        }

        foreach (self::DATE_DATA_FIELDS as $csvColumn => $typeTitle) {
            $value = trim($row[$csvColumn] ?? '');
            if ($value === '') {
                continue;
            }
            $fields[] = ['typeTitle' => $typeTitle, 'value' => $this->wrapHtml($value)];
        }

        return $fields;
    }

    private function applyCreate(array $create, int $locationId, array $fieldSkips = []): void
    {
        $directFields = ['Title' => $create['title']];
        $dataFields = [];
        $typeTitle = null;

        foreach (self::enumerateCreateFields($create) as $index => $field) {
            if (!empty($fieldSkips[$index])) {
                continue;
            }
            if ($field['kind'] === 'direct' && $field['key'] === 'TypeTitle') {
                $typeTitle = $field['value'];
            } elseif ($field['kind'] === 'direct') {
                $directFields[$field['key']] = $field['value'];
            } else {
                $dataFields[] = ['typeTitle' => $field['key'], 'value' => $field['value']];
            }
        }

        $experience = Experience::create($directFields);
        $experience->ParentID = $locationId;
        if ($typeTitle) {
            $experience->TypeID = $this->findOrCreateExperienceType($typeTitle)->ID;
        }
        $experience->write();

        foreach ($dataFields as $dataField) {
            $dataType = $this->findOrCreateExperienceDataType($dataField['typeTitle']);
            $data = ExperienceData::create([
                'ParentID' => $experience->ID,
                'TypeID' => $dataType->ID,
                'Description' => $dataField['value'],
            ]);
            $data->write();
        }

        if ($create['trainCount']) {
            $this->addTrains($experience, $create['trainCount']);
        }
    }

    private function applyFieldChange(array $change): void
    {
        $experience = Experience::get()->byID($change['experienceId']);
        if (!$experience) {
            return;
        }

        if ($change['field'] === 'trains') {
            $this->addTrains($experience, $change['newValue']);
            return;
        }

        [$kind, $key] = explode(':', $change['field'], 2) + [null, null];

        if ($kind === 'direct' && $key === 'TypeTitle') {
            $experience->TypeID = $this->findOrCreateExperienceType($change['newValue'])->ID;
            $experience->write();
            return;
        }

        if ($kind === 'direct') {
            $experience->$key = $change['newValue'];
            $experience->write();
            return;
        }

        if ($kind === 'data') {
            $dataType = $this->findOrCreateExperienceDataType($key);
            $data = $experience->ExperienceData()->filter('TypeID', $dataType->ID)->first();
            if (!$data) {
                $data = ExperienceData::create([
                    'ParentID' => $experience->ID,
                    'TypeID' => $dataType->ID,
                ]);
            }
            $data->Description = $change['newValue'];
            $data->write();
        }
    }

    private function addTrains(Experience $experience, int $wantedCount): void
    {
        $currentCount = $experience->ExperienceTrains()->count();
        for ($number = $currentCount + 1; $number <= $wantedCount; $number++) {
            $train = ExperienceTrain::create([
                'Title' => (string) $number,
                'SortOrder' => $number,
                'ParentID' => $experience->ID,
            ]);
            $train->write();
        }
    }

    private function findOrCreateExperienceType(string $title): ExperienceType
    {
        $type = ExperienceType::get()->filter('Title', $title)->first();
        if ($type) {
            return $type;
        }
        $type = ExperienceType::create(['Title' => $title, 'PluralName' => $title . 's']);
        $type->write();
        return $type;
    }

    private function findOrCreateExperienceDataType(string $title): ExperienceDataType
    {
        $type = ExperienceDataType::get()->filter('Title', $title)->first();
        if ($type) {
            return $type;
        }
        $type = ExperienceDataType::create(['Title' => $title, 'PluralName' => $title . 's']);
        $type->write();
        return $type;
    }

    private function wrapHtml(string $value): string
    {
        return '<p>' . $value . '</p>';
    }

    private function normalizeText(string $value): string
    {
        $value = strip_tags($value);
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
        $value = preg_replace('/\s+/', ' ', $value);
        return strtolower(trim($value));
    }

    private function formatThousands(string $value): string
    {
        return number_format((float) $value, 0, ',', '.');
    }

    private function formatMillions(float $value): string
    {
        $millions = $value / 1000000;
        if (fmod($millions, 1.0) === 0.0) {
            return (string) (int) $millions;
        }
        return rtrim(rtrim(number_format($millions, 1, '.', ''), '0'), '.');
    }

    private function parseInt(string $value): ?int
    {
        $value = trim($value);
        if ($value === '' || !is_numeric($value)) {
            return null;
        }
        return (int) $value;
    }

    /**
     * Only accepts a fully specified TT.MM.JJJJ date - incomplete dates
     * (e.g. just a year) are intentionally not parsed here, so they never
     * end up in the OpeningDate/ClosingDate field. They're still captured
     * as-is via DATE_DATA_FIELDS.
     */
    private function parseDate(string $raw): ?string
    {
        if ($raw === '') {
            return null;
        }
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $raw, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }
        return null;
    }
}
