<?php
namespace Application\Form;

use Zend\Form\Form;
//use Zend\Form\Element\Captcha;
//use Zend\Captcha\ReCaptcha;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Manager\ApplicationManager;

class RegistrationForm extends Form implements ServiceLocatorAwareInterface
{
    const DATE_OF_BIRTH_START_YEAR = 1920;
    protected $captcha;
    protected $serviceLocator;

    private function getDays()
    {
        $days = array();
        for ($i = 1; $i <= 31; $i++) {
            $day = ($i < 10) ? ('0' . $i) : $i;
            $days[$day] = $day;
        }
        return $days;
    }


    private function getMonths()
    {
        return array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        );
    }

    private function getYears()
    {
        $years = array();
        $current_year = date('Y');
        for ($i = $current_year; $i >= self::DATE_OF_BIRTH_START_YEAR; $i--) {
            $years[$i] = $i;
        }
        return $years;
    }

    public function getServiceLocator ()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function __construct(ServiceLocatorInterface $serviceLocator = null)
    {
        parent::__construct('register');
        $this->setAttribute('method', 'post')
            ->setAttribute('enctype', 'multipart/form-data')
            ->setServiceLocator($serviceLocator)
            ->setInputFilter($this->getServiceLocator()->get('Application\Form\Filter\RegistrationFilter')->getInputFilter());
        //Title
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Title',
                'empty_option' => 'Please select',
                'value_options' => array(
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Miss' => 'Miss',
                    'Ms' => 'Ms',
                ),
            )
        ));

        //First Name
        $this->add(array(
            'name' => 'first_name',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'First Name',
            ),
            'attributes' => array(
                'class' => 'required'
            )
        ));

        //Last Name
        $this->add(array(
            'name' => 'last_name',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));

        //Email
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        //Confirm Email
        $this->add(array(
            'name' => 'confirm_email',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Confirm Email',
            ),
        ));

        //Password
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
        //Confirm Password
        $this->add(array(
            'name' => 'confirm_password',
            'attributes' => array(
                'type' => 'password',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));

        //Country
        $this->add(array(
            'name' => 'country',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Country',
                'empty_option' => 'Please select',
                'value_options' => ApplicationManager::getInstance($this->getServiceLocator())->getCountriesSelectOptions()
            )
        ));

        /******************Date Of Birth***************/

        //Day
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'day',
            'options' => array(
                'value_options' => $this->getDays()
            )
        ));
        //Month
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'month',
            'options' => array(
                'value_options' => $this->getMonths()
            )
        ));
        //Year
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'year',
            'options' => array(
                'value_options' => $this->getYears()
            )
        ));
        $this->add(array(
            'name' => 'date_of_birth',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        /******************End Date of Birth***********/
        //Gender
        $this->add(array(
            'name' => 'gender',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Gender',
                'empty_option' => 'Please select',
                'value_options' => array(
                    'Male' => 'Male',
                    'Female' => 'Female'

                ),
            )
        ));

        //Display Name
        $this->add(array(
            'name' => 'display_name',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Display Name',
            ),
        ));

        //Avatar
        $this->add(array(
            'name' => 'avatar',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Avatar',
            ),
        ));
        //Default Avatars
        /*$this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'default_avatar',
            'options' => array(
                'label' => 'Or select a new avatar',
                'value_options' => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4
                )
            ),
        ));*/

        //TODO set checked by admin
        //Term 1
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'term1',
            'options' => array(
                'label' => 'Term 1',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            )
        ));

        //TODO set checked by admin
        //Term 2
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'term2',
            'options' => array(
                'label' => 'Term 2',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            )
        ));

        //Captcha
        /*$this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => 'Please verify you are human',
                'captcha' => $this->getServiceLocator()->get('Zend\Captcha\ReCaptcha'),
            ),
        ));*/
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
        // Submit
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Register',
                'id' => 'submitbutton',
            ),
        ));


    }

    public function prepareData()
    {
        //Set date of birth
        $data = $this->data;
        $day = isset($data['day']) ? $data['day'] : null;
        $month = isset($data['month']) ? $data['month'] : null;
        $year = isset($data['year']) ? $data['year'] : null;
        $data['date_of_birth'] = $year . '-' . $month . '-' . $day;
        $this->setData($data);
    }
}

