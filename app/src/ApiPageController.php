<?php

namespace {

    use SilverStripe\Security\Member;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\Core\Injector\Injector;
    use SilverStripe\Security\IdentityStore;
    use Level51\JWTUtils\JWTUtils;
    use Level51\JWTUtils\JWTUtilsException;
    use SilverStripe\Security\Security;
    use SilverStripe\CMS\Controllers\ContentController;

    /**
 * Class \PageController
 *
 * @property \ApiPage $dataRecord
 * @method \ApiPage data()
 * @mixin \ApiPage
 */
    class ApiPageController extends ContentController
    {
        private static $allowed_actions = [
            "login",
            "logout",
            "token",
            "checklogin",
        ];

        public function logout(HTTPRequest $request)
        {
            if ($member = Security::getCurrentUser()) {
                Injector::inst()->get(IdentityStore::class)->logOut($request);
            }
            return $this->redirect('home');
        }

        public function login(HTTPRequest $request)
        {
            if ($this->getCurrentUser() != null) {
                return "true";
            }
            if ($request->isPOST()) {
                $data = $request->postVars();
                $member = Member::get()->filter(['Email' => $data['Email']])->first();
                if ($member) {
                    if ($member->checkPassword($data['Password'])) {
                        $identityStore = Injector::inst()->get(IdentityStore::class);
                        $identityStore->logIn($member, false, $this->getRequest());
                        return json_encode("true");
                    }
                } else {
                    return json_encode("false");
                }
            } else {
                return json_encode("false");
            }
        }

        public function token(HTTPRequest $request)
        {
            try {
                $payload = JWTUtils::inst()->byBasicAuth($request);

                return json_encode($payload);
            } catch (JWTUtilsException $e) {
                return $this->httpError(403, $e->getMessage());
            }
        }

        public function getCurrentUser()
        {
            return Security::getCurrentUser();
        }

        protected function init()
        {
            parent::init();
        }
    }
}
