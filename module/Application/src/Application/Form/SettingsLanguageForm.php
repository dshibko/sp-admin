<?php
namespace Application\Form;

use Zend\Form\Form;
use \Application\Form\Filter\SettingsLanguageFilter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Manager\ApplicationManager;
use Application\Manager\LanguageManager;

class SettingsLanguageForm extends Form {
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
    public function __construct($type = 'change_language', ServiceLocatorInterface $serviceLocator) {
        parent::__construct();
        $this->setInputFilter(new SettingsLanguageFilter());
        $this->setAttribute('method', 'post')->setAttribute('id', 'settings-change-language');
        $this->setType($type)->setServiceLocator($serviceLocator);

        //Language
        $this->add(array(
            'name' => 'language',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Your Language',
                'empty_option' => 'Please Select',
                'value_options' => LanguageManager::getInstance($this->getServiceLocator())->getLanguagesSelectOptions(),
            ),
            'attributes' => array(
                'value' => ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser()->getLanguage()->getId(),
                'class' => 'required'
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