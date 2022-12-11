<?php
namespace App\Extensions;

use SilverStripe\Security\MemberAuthenticator\MemberLoginForm;

class MysiteLoginForm extends MemberLoginForm
{
    public function dologin($data)
    {
        $this->controller->redirect("/profile");
    }
}
