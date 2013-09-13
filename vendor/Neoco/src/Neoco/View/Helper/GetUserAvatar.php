<?php

namespace Neoco\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 *
 */
class GetUserAvatar extends AbstractHelper
{

    /**
     * @param string $avatarPath
     * @param string|null $facebookId
     * @param int $facebookAvatarSize
     * @return string
     */
    public function __invoke($avatarPath, $facebookId = null, $facebookAvatarSize = 45)
    {
        return $facebookId != null ?
            "http://graph.facebook.com/$facebookId/picture?width=$facebookAvatarSize&height=$facebookAvatarSize" : $avatarPath;
    }

}