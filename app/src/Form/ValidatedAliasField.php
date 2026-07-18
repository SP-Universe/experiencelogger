<?php

namespace App\Form;

use Override;
use SilverStripe\Core\Validation\ValidationResult;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;

class ValidatedAliasField extends TextField
{
    #[Override]
    public function validate(): ValidationResult
    {
        $result = ValidationResult::create();
        $alias = $this->getFormattedValue();
        $member = Member::get()->filter(['Nickname' => $alias])->first();

        if ($member) {
            $result->addFieldError(
                $this->name,
                'Nickname is already in use',
                'validation'
            );
        }
        return $result;
    }
}
