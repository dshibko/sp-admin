<?php

namespace Application\Form;

use Zend\Form\Form;
use Application\Manager\ApplicationManager;
use Application\Manager\LanguageManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Form\Filter\SetUpFormFilter;
use Application\Model\Entities\Language;
use Application\Model\Entities\Country;

class SetUpForm extends Form implements ServiceLocatorAwareInterface{

    public function getServiceLocator ()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function __construct(ServiceLocatorInterface $serviceLocator = null) {
        parent::__construct('setup');
        $this->setAttribute('method', 'post');
        $this->setServiceLocator($serviceLocator)
             ->setInputFilter(new SetUpFormFilter());

        //Language
        $this->add(array(
            'name' => 'language',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Language',
                'empty_option' => 'Please Select',
                'value_options' => LanguageManager::getInstance($this->getServiceLocator())->getLanguagesSelectOptions(),
            ),
            'attributes' => array(
                'value' => ''
            )

        ));

        //Region
        $this->add(array(
            'name' => 'region',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Region',
                'empty_option' => 'Please Select',
                'value_options' => ApplicationManager::getInstance($this->getServiceLocator())->getCountriesSelectOptions(),

            ),
            'attributes' => array(
                'value' => ''
            )

        ));
        //CSRF
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'OK',
                'id' => 'submitbutton',
            ),
        ));
    }
}