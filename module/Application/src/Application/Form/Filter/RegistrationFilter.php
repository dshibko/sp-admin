<?php
namespace Application\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\DAOs\UserDAO;
use Zend\Validator\NotEmpty;
use Neoco\Validator\BadWordValidator;

class RegistrationFilter implements InputFilterAwareInterface
{
    const INPUT_MIN_LENGTH = 3;
    const NAME_MAX_LENGTH = 30;
    const EMAIL_MAX_LENGTH = 50;
    const PASSWORD_MIN_LENGTH = 6;
    const PASSWORD_MAX_LENGTH = 15;
    const DISPLAY_NAME_MAX_LENGTH = 20;

    protected $inputFilter;
    protected $repository;
    protected $serviceLocator;
    protected $terms;

    /**
     * @param mixed $terms
     * @return $this
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTerms()
    {
        return $this->terms;
    }

    public function __construct(ServiceLocatorInterface $serviceLocator){
          $this->setServiceLocator($serviceLocator);
    }
    public function setRepository($repository){
        $this->repository = $repository;
        return $this;
    }

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
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
                )
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
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
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
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
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
                            'min' => self::INPUT_MIN_LENGTH,
                            'max' => self::EMAIL_MAX_LENGTH,
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
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
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
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
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
                )
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
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
                ),
            )));

            //Country
            $inputFilter->add($factory->createInput(array(
                'name' => 'country',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
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
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
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
                    $this->getServiceLocator()->get('badWordValidator'),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array()
                    )
                ),
            )));

            $inputFilter->add($factory->createInput(array(
               'name' => 'avatar',
               'required' => false
            )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'default_avatar',
                'required' => true,
                'inarrayvalidator' => false
            )));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
