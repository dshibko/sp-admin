<?php
namespace Application\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\DAOs\UserDAO;

class RegistrationFilter implements InputFilterAwareInterface
{
    const INPUT_MIN_LENGTH = 1;
    const NAME_MAX_LENGTH = 30;
    const EMAIL_MAX_LENGTH = 50;
    const PASSWORD_MIN_LENGTH = 6;
    const PASSWORD_MAX_LENGTH = 15;
    const DISPLAY_NAME_MAX_LENGTH = 20;

    protected $inputFilter;
    protected $repository;

    public function __construct(ServiceLocatorInterface $serviceLocator){
          $this->setServiceLocator($serviceLocator);
    }
    public function setRepository($repository){
        $this->repository = $repository;
        return $this;
    }

    public function getRepository(){
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
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            //Title
            $inputFilter->add($factory->createInput(array(
                'name' => 'title',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));
            //First Name
            $inputFilter->add($factory->createInput(array(
                'name' => 'first_name',
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
                            'min' => self::INPUT_MIN_LENGTH,
                            'max' => self::NAME_MAX_LENGTH,
                        ),
                    ),
                ),
            )));

            //Last Name
            $inputFilter->add($factory->createInput(array(
                'name' => 'last_name',
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
                            'min' => self::INPUT_MIN_LENGTH,
                            'max' => self::NAME_MAX_LENGTH,
                        ),
                    ),
                ),
            )));

            //Email
            $inputFilter->add($factory->createInput(array(
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
                            'object_repository' =>  $this->getRepository(),
                            'table' => 'users',
                            'fields' => array('email'),
                            'messages' => array(
                                'objectFound' => 'Email already taken'
                            )
                        )
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => self::INPUT_MIN_LENGTH,
                            'max' => self::EMAIL_MAX_LENGTH,
                        ),
                    ),
                ),
            )));

            //Confirm Email
            $inputFilter->add($factory->createInput(array(
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
                            'min' => self::INPUT_MIN_LENGTH,
                            'max' => self::EMAIL_MAX_LENGTH,
                        ),
                    ),
                    array(
                        'name' => 'identical',
                        'options' => array('token' => 'email'),
                    ),
                ),
            )));
            //TODO strength indicator
            //Password
            $inputFilter->add($factory->createInput(array(
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
                            'min' => self::PASSWORD_MIN_LENGTH,
                            'max' => self::PASSWORD_MAX_LENGTH,
                        ),
                    ),
                ),
            )));

            //Confirm Password
            $inputFilter->add($factory->createInput(array(
                'name' => 'confirm_password',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => self::PASSWORD_MIN_LENGTH,
                            'max' => self::PASSWORD_MAX_LENGTH,
                        ),
                    ),
                    array(
                        'name' => 'identical',
                        'options' => array('token' => 'password'),
                    ),
                ),
            )));

            //Country
            $inputFilter->add($factory->createInput(array(
                'name' => 'country',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                )
            )));

            // Date of birth
            $inputFilter->add($factory->createInput(array(
                'name' => 'date_of_birth',
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'Zend\Validator\Date',
                        'options' => array(
                            'messages' => array(
                                'dateInvalidDate' => 'Invalid Date of birth'

                            )
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'gender',
                'required' =>false,
            )));
            //Display Name
            $inputFilter->add($factory->createInput(array(
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
                            'min' => self::INPUT_MIN_LENGTH,
                            'max' => self::DISPLAY_NAME_MAX_LENGTH,
                        ),
                    ),
                    array(
                        'name' => 'Application\Form\Validator\BadWordValidator',
                        'options' => array()
                    )
                ),
            )));

            $inputFilter->add($factory->createInput(array(
               'name' => 'avatar',
               'required' => false
            )));
            /*$inputFilter->add($factory->createInput(array(
                'name' => 'default_avatar',
                'required' => true,
                'inarrayvalidator' => false
            )));*/
            //TODO set compulsory by admin
            $inputFilter->add($factory->createInput(array(
                'name' => 'term1',
                'required' => true,
            )));
            //TODO set compulsory by admin
            $inputFilter->add($factory->createInput(array(
                'name' => 'term2',
                'required' => true,
            )));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
