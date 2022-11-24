<?php

namespace {

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\IdentityStore;

    use SilverStripe\Security\Security;
    use Symbiote\MemberProfiles\Pages\MemberProfilePage;

    use SilverStripe\CMS\Controllers\ContentController;

    /**
 * Class \PageController
 *
 * @property \Page dataRecord
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
            //$session = $this->getRequest()->getSession();
            //$session->set("PWD" . $this->URLSegment, "");
            //return $this->redirect($this->Link());

            Injector::inst()->get(IdentityStore::class)->logOut($request);

            return $this->redirect('home');
        }

        public function getCurrentUser()
        {
            return Security::getCurrentUser();
        }

        public function getProfilePage()
        {
            //return MemberProfilePage::get()->first();
        }

        protected function init()
        {
            parent::init();
            // You can include any CSS or JS required by your project here.
            // See: https://docs.silverstripe.org/en/developer_guides/templates/requirements/
        }
    }
}
