<?php

namespace {

    use App\Profile\DashboardPage;
    use App\Profile\LoginPage;
    use App\Profile\ProfilePage;
    use App\Profile\RegistrationPage;
    use SilverStripe\Security\Member;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\Core\Injector\Injector;
    use SilverStripe\Security\IdentityStore;

    use SilverStripe\Security\Security;
    use Symbiote\MemberProfiles\Pages\MemberProfilePage;

    use SilverStripe\CMS\Controllers\ContentController;

    /**
 * Class \PageController
 *
 * @property \AppPage $dataRecord
 * @method \AppPage data()
 * @mixin \AppPage
 */
    class AppPageController extends ContentController
    {
        /**
         * An array of actions that can be accessed via a request. Each array element should be an action name, and the
         * permissions or conditions required to allow the user to access it.
         *
         * <code>
         * [
         *     'action', // anyone can access this action
         *     'action' => true, // same as above
         *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
         *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
         * ];
         * </code>
         *
         * @var array
         */
        private static $allowed_actions = [
            "logout",
            "login"
        ];

        public function login($request)
        {
            $session = $request->getSession();
            $profilePage = DashboardPage::get()->first();
            $link = $profilePage->Link();
            $session->set("BackURL", $link);
            return $this->redirect("Security/Login");
        }

        public function logout(HTTPRequest $request)
        {
            if ($member = Security::getCurrentUser()) {
                Injector::inst()->get(IdentityStore::class)->logOut($request);
            }
            return $this->redirect('home');
        }

        public function getCurrentUser()
        {
            return Security::getCurrentUser();
        }
    }
}
