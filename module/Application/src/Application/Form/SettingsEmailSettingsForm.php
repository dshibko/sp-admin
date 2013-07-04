<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Manager\ApplicationManager;

class SettingsEmailSettingsForm extends Form {
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
    public function __construct($type = 'change_email_settings', ServiceLocatorInterface $serviceLocator) {
        parent::__construct();
        $this->setAttribute('method', 'post')->setAttribute('id', 'settings-change-email-settings');
        $this->setType($type)->setServiceLocator($serviceLocator);

        //Email Settings
        //Term 1
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'term1',
            'options' => array(
                'label' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
        ));

        //Term 2
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'term2',
            'options' => array(
                'label' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
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