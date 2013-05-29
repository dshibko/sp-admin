<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Application\Form\Filter\RegistrationFilter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class SettingsDisplayNameFilter extends InputFilter
{
    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        $factory = new InputFactory();

        //Display Name
        $this->add($factory->createInput(array(
            'name' => 'display_name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::INPUT_MIN_LENGTH,
                        'max' => RegistrationFilter::DISPLAY_NAME_MAX_LENGTH,
                    ),
                ),
                $this->getServiceLocator()->get('badWordValidator')
            ),
        )));
    }
}