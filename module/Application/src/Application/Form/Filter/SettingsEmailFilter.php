<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Application\Form\Filter\RegistrationFilter;
use Application\Model\DAOs\UserDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SettingsEmailFilter extends InputFilter
{
    protected $repository;
    protected $serviceLocator;

    public function getUserRepository(){
        if (null === $this->repository){
            $this->repository = UserDAO::getInstance($this->getServiceLocator())->getRepository();
        }
        return $this->repository;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function setRepository($repository){
        $this->repository = $repository;
        return $this;
    }

    function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        $factory = new InputFactory();

        //Email
        $this->add($factory->createInput(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(),
                ),
                array(
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => array(
                        'object_repository' =>  $this->getUserRepository(),
                        'table' => 'users',
                        'fields' => array('email'),
                        'messages' => array(
                            'objectFound' => 'Already taken'
                        )
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::INPUT_MIN_LENGTH,
                        'max' => RegistrationFilter::EMAIL_MAX_LENGTH,
                    ),
                ),
            ),
        )));

        //Confirm Email
        $this->add($factory->createInput(array(
            'name' => 'confirm_email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::INPUT_MIN_LENGTH,
                        'max' => RegistrationFilter::EMAIL_MAX_LENGTH,
                    ),
                ),
                array(
                    'name' => 'identical',
                    'options' => array('token' => 'email'),
                ),
            ),
        )));
    }
}