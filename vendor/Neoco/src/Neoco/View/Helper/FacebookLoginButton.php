<?php

namespace Neoco\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\Request;

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
     * @param  string $key
     * @return string
     */
    public function __invoke()
    {
        $uri = $this->getRequest()->getUri();
        $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        return '<a href="' .$this->getFacebookAPI()->getLoginUrl(array(
            'scope' => $this->getScope(),
            'redirect_uri' => $base.'/facebook'
        )).'">Connect with Facebook</a>';
    }
}