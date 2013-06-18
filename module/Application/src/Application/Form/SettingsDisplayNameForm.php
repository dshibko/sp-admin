<?php
namespace Application\Form;

use Zend\Form\Form;
use \Application\Form\Filter\SettingsDisplayNameFilter;
use Application\Form\Filter\RegistrationFilter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Manager\ApplicationManager;

class SettingsDisplayNameForm extends Form {
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
    public function __construct($type = 'change_display_name', ServiceLocatorInterface $serviceLocator) {
        parent::__construct();

        $this->setAttribute('method', 'post')->setAttribute('id', 'settings-change-display-name');
        $this->setType($type)->setServiceLocator($serviceLocator);
        $this->setInputFilter(new SettingsDisplayNameFilter($serviceLocator));
        //Display Name
        $this->add(array(
            'name' => 'display_name',
            'options' => array(
                'label' => 'Display Name',
            ),
            'attributes' => array(
                'class' => 'required',
                'type' => 'text',
                'value' => ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser()->getDisplayName(),
                'maxlength' => RegistrationFilter::DISPLAY_NAME_MAX_LENGTH
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