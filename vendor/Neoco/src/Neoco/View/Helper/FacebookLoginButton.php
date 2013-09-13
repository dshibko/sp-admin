<?php

namespace Neoco\View\Helper;

use Zend\Soap\Server;
use Zend\View\Helper\AbstractHelper;
use Zend\Http\Request;
use Zend\View\Helper\ServerUrl;

class FacebookLoginButton extends AbstractHelper
{
    protected $request;
    /**
     * @var \BaseFacebook
     */
    protected $facebookAPI;
    /**
     * @var string
     */
    protected $scope;

    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }
    public function getScope()
    {
        return $this->scope;
    }
    public function getFacebookAPI()
    {
        return $this->facebookAPI;
    }
    public function setFacebookAPI(\BaseFacebook $facebookAPI)
    {
        $this->facebookAPI = $facebookAPI;
        return $this;
    }
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $label
     * @return string
     */
    public function __invoke($label = 'Connect with Facebook')
    {
        $serverUrl = new ServerUrl();
        return '<a href="' .$this->getFacebookAPI()->getLoginUrl(array(
            'scope' => $this->getScope(),
            'redirect_uri' => $serverUrl().'/facebook'
        )).'" class="login-with-facebook"><span>'.$label.'</span></a>';
    }
}