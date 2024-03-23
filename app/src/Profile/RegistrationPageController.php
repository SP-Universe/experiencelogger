<?php

namespace App\Profile;

use SilverStripe\Forms\Form;
use App\Form\ValidatedAliasField;
use App\Form\ValidatedEmailField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Security\Security;
use App\Form\ValidatedPasswordField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\CMS\Controllers\ContentController;
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

    public function index()
    {
        if (Security::getCurrentUser()) {
            return $this->redirect('./profile');
        }
        return $this->render();
    }

    public function registerForm()
    {
        $fields = new FieldList(
            ValidatedAliasField::create('Nickname', 'Nickname')->addExtraClass('text'),
            TextField::create('FirstName', 'FirstName')->setTitle('First Name'),
            TextField::create('Surname', 'Surname')->setTitle('Last Name'),
            DateField::create('DateOfBirth', 'DateOfBirth')->setTitle('Birthday'),
            ValidatedEmailField::create('Email', 'Email'),
            ValidatedPasswordField::create('Password', 'Password'),
        );

        $actions = new FieldList(
            FormAction::create(
                'doRegister',   // methodName
                'Register'      // Label
            )
        );

        $required = new RequiredFields('alias', 'email', 'password', 'firstname', 'lastname', 'birthday');

        $form = new Form($this, 'RegisterForm', $fields, $actions, $required);

        return $form;
    }

    public function doRegister($data, Form $form)
    {
        /*
         * Check if the fields clear their validation rules.
         * If there are errors, then the form will be updated with the errors
         * so the user may correct them.
         */
        $validationResults = $form->validationResult();

        if ($validationResults->isValid()) {
            $member = Member::create();
            $form->saveInto($member);
            $member->write();

            $form->sessionMessage('Registration successful', 'good');

            $message = _t(
                '',
                'Hey {firstname}! Welcome! You can now login:',
                ['firstname' => $member->FirstName]
            );
            Security::singleton()->setSessionMessage($message, ValidationResult::TYPE_GOOD);

            return $this->redirect('./Security/login');
        }

        return $this->redirectBack();
    }
}
