<?php use SaltedHerring\Debugger;

class Signup extends Page_Controller
{
    /**
     * Defines methods that can be called directly
     * @var array
     */
    private static $allowed_actions = array(
        'SignupForm',
        'activate'
    );

    public function index($request)
    {
		// if ($member = Member::currentUser()) {
		// 	return $this->redirect('/member');
		// }

		return $this->renderWith(array('Signup', 'Page'));
	}

    public function activate($request) {
		$member_id = $request->getVar('id');
		$key = $request->getVar('token');
		if ($member = Member::get()->byID($member_id)) {
			if (empty($member->ValidationKey)) {
				$content = 'Already activated';
			} elseif ($member->ValidationKey == $key) {
				$content = 'Your account has been activated. Your will be redirected in a few seconds...';
				$member->ValidationKey = null;
				$member->write();
				$member->login();
			} else {
				$content = "not match!";
			}
		} else {
			$content = 'No such member';
		}

		return $this->customise(new ArrayData(array('Title' => 'Activation', 'Content' => $content)))->renderWith(array('Page'));
	}

    public function SignupForm()
    {
        return new SignupForm($this);
    }

    public function Link($action = NULL) {
		return 'signup';
	}

    public function Title()
    {
        return 'Signup';
    }

    public function ShowOff()
    {

    }

}
