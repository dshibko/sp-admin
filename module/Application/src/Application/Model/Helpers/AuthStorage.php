<?php

namespace Application\Model\Helpers;

use \Zend\Session\SessionManager;
use \Zend\Authentication\Storage\Session;

class AuthStorage extends Session {

    public function __construct($namespace = 'auth', $member = null, SessionManager $manager = null)
    {
        parent::__construct($namespace, $member, $manager);
    }

    public function setRememberMe($rememberMe = 0, $time = 1209600) {
        if ($rememberMe == 1)
            $this->session->getManager()->rememberMe($time);
    }

    public function forgetMe() {
        $this->session->getManager()->forgetMe();
    }

}
