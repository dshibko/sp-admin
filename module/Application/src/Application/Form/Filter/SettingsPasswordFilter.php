<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Application\Form\Filter\RegistrationFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

class SettingsPasswordFilter extends InputFilter
{
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

    function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $factory = new InputFactory();
        $this->setServiceLocator($serviceLocator);
        //Old Password
        $this->add($factory->createInput(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::PASSWORD_MIN_LENGTH,
                        'max' => RegistrationFilter::PASSWORD_MAX_LENGTH,
                    ),
                ),
               $this->getServiceLocator()->get('confirmPasswordValidator')
            ),
        )));

        //New Password
        $this->add($factory->createInput(array(
            'name' => 'new_password',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::PASSWORD_MIN_LENGTH,
                        'max' => RegistrationFilter::PASSWORD_MAX_LENGTH,
                    ),
                ),
            ),
        )));

        //Confirm New Password
        $this->add($factory->createInput(array(
            'name' => 'confirm_new_password',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::PASSWORD_MIN_LENGTH,
                        'max' => RegistrationFilter::PASSWORD_MAX_LENGTH,
                    ),
                ),
                array(
                    'name' => 'identical',
                    'options' => array('token' => 'new_password'),
                ),
            ),
        )));
    }
}