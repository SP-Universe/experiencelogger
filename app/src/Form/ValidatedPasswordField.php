<?php

namespace App\Form;

use Override;
use SilverStripe\Core\Validation\ValidationResult;
use SilverStripe\Forms\PasswordField;

class ValidatedPasswordField extends PasswordField
{
    #[Override]
    public function validate(): ValidationResult
    {
        $result = ValidationResult::create();
        $value = $this->getFormattedValue();
        if (strlen($value) < 6) {
            $result->addFieldError(
                $this->name,
                'Password must be at least 6 characters long',
                'validation'
            );
        }
        return $result;
    }
}
