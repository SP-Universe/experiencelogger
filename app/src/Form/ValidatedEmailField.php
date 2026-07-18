<?php

namespace App\Form;

use Override;
use SilverStripe\Core\Validation\ValidationResult;
use SilverStripe\Forms\EmailField;
use SilverStripe\Security\Member;

class ValidatedEmailField extends EmailField
{
    #[Override]
    public function validate(): ValidationResult
    {
        $result = ValidationResult::create();
        $email = $this->getFormattedValue();
        $member = Member::get()->filter(['Email' => $email])->first();

        if ($member) {
            $result->addFieldError(
                $this->name,
                'Email is already in use',
                'validation'
            );
        }
        return $result;
    }
}
