<?php

namespace {

    use App\Profile\DashboardPage;
    use App\Profile\ProfilePage;
    use App\Profile\RegistrationPage;
    use App\User\User;
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
            $currentMember = Security::getCurrentUser();
            if (!$currentMember) {
                return null;
            }
            $currentUser = User::get()->filter("ID", $currentMember->UserID)->first();
            return $currentUser;
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

        public function getLoadingText()
        {
            $randomLoadingTexts = array(
                "Waxing the Coaster tracks...",
                "Preparing Popcorn...",
                "Cuddling Mascots...",
                "Running test drives...",
                "Opening the Queuelines...",
                "Designing the Rollercoaster...",
                "Checking the Safety Harness...",
                "Polishing the Rollercoaster...",
                "Checking the Rollercoaster tracks...",
                "Checking the Rollercoaster brakes...",
                "Checking the Rollercoaster wheels...",
                "Checking the Rollercoaster seats...",
                "Preparing the mascots...",
                "Fueling the fog machines...",
                "Designing Tickets...",
                "Testing the trimbrakes...",
                "Testing the launch system...",
                "Starting animatronics...",
                "Checking the animatronics...",
                "Turning on the speakers...",
                "Composing the soundtrack...",
                "Cooking Burgers...",
                "Cooking Hotdogs...",
                "Cooking Fries...",
                "Cooking Pizza...",
                "Cooking Popcorn...",
                "Freezing Icecream...",
                "Baking Cookies...",
                "Inspiring team members...",
                "Preparing Shows...",
                "Preparing Parades...",
                "Preparing Fireworks...",
            );

            return $randomLoadingTexts[array_rand($randomLoadingTexts)];
        }
    }
}
