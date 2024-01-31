<?php

namespace {

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
 * @property \Page $dataRecord
 * @method \Page data()
 * @mixin \Page
 */
    class PageController extends ContentController
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
        ];

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

        public function getProfilePage()
        {
            return ProfilePage::get()->first();
        }

        public function getRegistrationPage()
        {
            return RegistrationPage::get()->first();
        }

        public function getHomepageLink()
        {
            $homepage = Page::get()->filter(['URLSegment' => 'home'])->first()->Link();
            if ($homepage) {
                return $homepage;
            } else {
                echo "ERROR: Homepage not found";
            }
        }

        protected function init()
        {
            parent::init();
            // You can include any CSS or JS required by your project here.
            // See: https://docs.silverstripe.org/en/developer_guides/templates/requirements/
        }
    }
}
