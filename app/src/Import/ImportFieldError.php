<?php

namespace App\Import;

use RuntimeException;

/**
 * Thrown by ExperienceCsvImporter::apply() when writing a single plan entry
 * fails SilverStripe's field type validation (e.g. "Must be an integer").
 * Carries enough context - which section of the plan, which entry, and
 * (for creates) which field within it - for the review form to redisplay
 * with the offending field highlighted, instead of just showing a bare
 * error message.
 */
class ImportFieldError extends RuntimeException
{
    public function __construct(
        public readonly string $section, // 'create' | 'autoFill' | 'conflict' | 'missing'
        public readonly int $planIndex,
        public readonly ?int $fieldIndex, // index into ExperienceCsvImporter::enumerateCreateFields(), 'create' only
        public readonly ?string $field, // raw field key, e.g. 'direct:HasSingleRider' or 'data:Height'
        string $message
    ) {
        parent::__construct($message);
    }
}
