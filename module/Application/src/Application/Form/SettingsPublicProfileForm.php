<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Manager\ApplicationManager;

class SettingsPublicProfileForm extends Form {
    protected $type;
    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
    public function __construct($type = 'change_public_profile', ServiceLocatorInterface $serviceLocator) {
        parent::__construct();
        $this->setAttribute('method', 'post')->setAttribute('id', 'settings-change-public-profile');
        $this->setType($type)->setServiceLocator($serviceLocator);

        //Public Profile
        $this->add(array(
            'name' => 'is_public',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Profile Type',
                'value_options' => array(
                    0 => 'Private',
                    1 => 'Public'
                ),
            ),
            'attributes' => array(
                'value' => ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser()->getIsPublic(),
            )

        ));

        $this->add(array(
            'name' => 'type',
            'type'  => 'hidden',
            'attributes' => array(
                'value' => $this->getType()
            ),

        ));
        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Save changes',
                'id' => 'submitbutton',
            ),
        ));
    }
}