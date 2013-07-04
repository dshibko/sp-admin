<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Application\Model\DAOs\UserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminFormFilter extends InputFilter
{
    const DISPLAY_NAME_MAX_LENGTH = 20;
    const NAME_MAX_LENGTH = 30;
    const EMAIL_MAX_LENGTH = 50;

    protected $repository;
    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    public function setRepository($repository)
    {
        $this->repository = $repository;
        return $this;
    }

    public function getUserRepository()
    {
        if (null === $this->repository){
            $this->repository = UserDAO::getInstance($this->getServiceLocator())->getRepository();
        }
        return $this->repository;
    }

    function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $factory = new InputFactory();
        $this->setServiceLocator($serviceLocator);

        //First Name
        $this->add($factory->createInput(array(
            'name'     => 'first_name',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => self::NAME_MAX_LENGTH,
                    ),
                ),
        ))));

        //Last Name
        $this->add($factory->createInput(array(
            'name'     => 'last_name',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => self::NAME_MAX_LENGTH,
                    ),
                ),
        ))));
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
                        'max' => self::DISPLAY_NAME_MAX_LENGTH,
                    ),
                ),
                $this->getServiceLocator()->get('badWordValidator')
            ),
        )));
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
                    ),

                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => self::EMAIL_MAX_LENGTH,
                    ),
                )
            ),
        )));
    }
}