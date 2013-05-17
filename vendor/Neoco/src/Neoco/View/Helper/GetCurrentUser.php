<?php

namespace Neoco\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetCurrentUser extends AbstractHelper
{
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

    /**
     * @return \Application\Model\Entities\User
     */
    public function __invoke()
    {
        return \Application\Manager\ApplicationManager::getInstance($this->serviceLocator)->getCurrentUser();
    }

}