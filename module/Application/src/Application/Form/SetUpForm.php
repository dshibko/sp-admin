<?php

namespace Application\Form;

use Zend\Form\Form;
use Application\Manager\ApplicationManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Form\Filter\SetUpFormFilter;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Application\Model\Entities\Language;
use Application\Model\Entities\Country;

class SetUpForm extends Form implements ServiceLocatorAwareInterface{

    const DEFAULT_COUNTRY_ISO_CODE = 'GB';
    const DEFAULT_LANGUAGE_ID = 1;
    const DEFAULT_COUNTRY_ID = 95;

    private $remoteAddress;
    private $country_code;
    private $country;

    private function getUserCountryCode()
    {
        if (null === $this->country_code){
            $this->country_code = geoip_country_code_by_name($this->getRemoteAddress()->getIpAddress());
        }

        return $this->country_code;
    }
    /**
     *  @return Application\Model\Entities\Country
    */
    private function getUserCountry()
    {
        if (null === $this->country){
            $isoCode = $this->getUserCountryCode();
            if (empty($isoCode)){
                $isoCode = self::DEFAULT_COUNTRY_ISO_CODE;
            }

            $country = ApplicationManager::getInstance($this->getServiceLocator())->getCountryByISOCode($isoCode);
            if (empty($country)){
                $country = ApplicationManager::getInstance($this->getServiceLocator())->getCountryByISOCode(self::DEFAULT_COUNTRY_ISO_CODE);
            }

            $this->country = $country;
        }

        return $this->country;

    }

    private function getUserLanguageId()
    {
        $language_id = self::DEFAULT_LANGUAGE_ID;
        $country = $this->getUserCountry();
        $language = $country->getLanguage();
        if (!empty($language) && $language instanceof Language){
            $language_id = $language->getId();
        }
        return $language_id;
    }

    private function getLanguages(){
        $data = ApplicationManager::getInstance($this->getServiceLocator())->getAllLanguages(true);
        $languages = array();
        if (!empty($data) && is_array($data)){
            foreach($data as $language){
                $languages[$language['id']] = $language['displayName'];
            }
        }
        return $languages;
    }

    private function getUserCountryId()
    {
        $country_id = self::DEFAULT_COUNTRY_ID;

        $country = $this->getUserCountry();
        if (!empty($country) && $country instanceof Country){
            $country_id = $country->getId();
        }
        return $country_id;
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

    public function __construct(ServiceLocatorInterface $serviceLocator = null) {
        parent::__construct('setup');
        $this->setRemoteAddress(new RemoteAddress());
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
                'value_options' => $this->getLanguages(),
            ),
            'attributes' => array(
                'value' => $this->getUserLanguageId()
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
                'value' => $this->getUserCountryId()
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

        //$this->setDefaults('language',1);
    }

    public function setRemoteAddress(RemoteAddress $remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
        return $this;
    }

    public function getRemoteAddress()
    {
        return $this->remoteAddress;
    }
}