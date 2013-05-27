<?php

namespace Neoco\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 *
 */
class ClubLogo extends AbstractHelper
{

    private $defaultLogo;

    public function setDefaultLogo($defaultLogo)
    {
        $this->defaultLogo = $defaultLogo;
    }

    /**
     * @param string $logo
     * @return string
     */
    public function __invoke($logo)
    {
        return !empty($logo) ? $logo : $this->defaultLogo;
    }

}