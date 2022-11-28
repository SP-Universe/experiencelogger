<?php

namespace App\Form;

use SilverStripe\Forms\EmailField;
use SilverStripe\Security\Member;

class ValidatedEmailField extends EmailField
{
    public function validate($validator)
    {
        $email = $this->Value();
        $member = Member::get()->filter(['Email' => $email])->first();

        if ($member) {
            $validator->validationError(
                $this->name,
                'Email is already in use',
                'validation'
            );
            return false;
        }
        return true;
    }
}
