<?php

namespace Neoco\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Manager\AvatarManager;

class DefaultAvatars extends AbstractHelper
{
    const DEFAULT_CHECKED_AVATAR_ID = 1;
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    private function getAvatarsMarkUp(array $avatars, $defaultAvatar = null)
    {
        $html = '';
        foreach ($avatars as $avatar){
            $checked = (($avatar['id'] == $defaultAvatar) || (is_null($defaultAvatar) && $avatar['id'] == self::DEFAULT_CHECKED_AVATAR_ID)) ? 'checked="checked"' : '';
            $html .= <<<AVATAR
                <label>
                    <input type="radio" value="{$avatar['id']}" {$checked} name="default_avatar"/>
                    <img src="{$avatar['smallImagePath']}" alt="Default avatar #{$avatar['id']}" />
                </label>
AVATAR;
        }
        return $html;
    }
    public function __invoke($defaultAvatar = null)
    {
        $avatars = AvatarManager::getInstance($this->serviceLocator)->getDefaultAvatars(true);
        if (!empty($avatars) && is_array($avatars)){
            return $this->getAvatarsMarkUp($avatars, $defaultAvatar);
        }
        return false;
    }
}