<?php

namespace Application\Manager;

use \Application\Model\DAOs\SeasonDAO;
use \Application\Model\Entities\Season;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\Entities\User;
use \Application\Model\DAOs\UserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ApplicationManager extends BasicManager {

    /**
     * @var ApplicationManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ApplicationManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ApplicationManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @var \Application\Model\Entities\User
     */
    protected $currentUser = -1;

    /**
     * @return \Application\Model\Entities\User
     */
    public function getCurrentUser()
    {
        if ($this->currentUser == -1) {
            $identity = $this->getServiceLocator()->get('AuthService')->getIdentity();
            if ($identity == null) $this->currentUser = null;
            else
                if ($identity instanceof User)
                    $this->currentUser = $identity;
                else if (is_string($identity))
                    $this->currentUser = UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($identity);
        }
        return $this->currentUser;
    }

    /**
     * @var \Application\Model\Entities\User
     */
    protected $currentSeason = -1;

    /**
     * @return \Application\Model\Entities\Season
     */
    public function getCurrentSeason()
    {
        if ($this->currentSeason == -1)
            $this->currentSeason = SeasonDAO::getInstance($this->getServiceLocator())->getCurrentSeason();
        return $this->currentSeason;
    }

}
