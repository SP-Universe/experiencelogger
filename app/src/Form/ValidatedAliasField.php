<?php

namespace App\Form;

use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;

class ValidatedAliasField extends TextField
{
    public function validate($validator)
    {
        $alias = $this->Value();
        $member = Member::get()->filter(['Nickname' => $alias])->first();

        if ($member) {
            $validator->validationError(
                $this->name,
                'Nickname is already in use',
                'validation'
            );
            return false;
        }
        return true;
    }
}
