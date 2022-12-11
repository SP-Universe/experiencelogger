<?php

namespace App\Profile;

use App\Form\ValidatedAliasField;
use App\Form\ValidatedEmailField;
use App\Form\ValidatedPasswordField;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Security\Member; // Will be used later when we do register a new member.

/**
 * Class \App\Profile\RegistrationController
 *
 * @property \App\Profile\RegistrationPage $dataRecord
 * @method \App\Profile\RegistrationPage data()
 * @mixin \App\Profile\RegistrationPage
 */
class RegistrationPageController extends ContentController
{
    private static $allowed_actions = [
        'registerForm'
    ];

    public function registerForm()
    {
        $fields = new FieldList(
            ValidatedAliasField::create('nickname', 'Nickname')->addExtraClass('text'),
            ValidatedEmailField::create('email', 'Email'),
            ValidatedPasswordField::create('password', 'Password'),
        );

        $actions = new FieldList(
            FormAction::create(
                'doRegister',   // methodName
                'Register'      // Label
            )
        );

        $required = new RequiredFields('alias', 'email', 'password');

        $form = new Form($this, 'RegisterForm', $fields, $actions, $required);

        return $form;
    }

    public function doRegister($data, Form $form)
    {
        // Make sure we have all the data we need
        $alias = $data['alias'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        /*
         * Check if the fields clear their validation rules.
         * If there are errors, then the form will be updated with the errors
         * so the user may correct them.
         */
        $validationResults = $form->validationResult();

        if ($validationResults->isValid()) {
            $member = Member::create();
            $member->FirstName = $alias;
            $member->Email = $email;
            $member->Password = $password;
            $member->write();

            $form->sessionMessage('Registration successful', 'good');
        }

        return $this->redirectBack();
    }
}
