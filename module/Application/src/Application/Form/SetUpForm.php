<?php

namespace Application\Form;

use Zend\Form\Form;
use Application\Manager\ApplicationManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Form\Filter\SetUpFormFilter;
use Zend\Http\PhpEnvironment\RemoteAddress;

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

    private function getUserCountry()
    {
        if (null === $this->country){
            $isoCode = $this->getUserCountryCode();
            if (empty($isoCode)){
                $isoCode = self::DEFAULT_COUNTRY_ISO_CODE;
            }

            $country = ApplicationManager::getInstance($this->getServiceLocator())->getCountryByISOCode($isoCode,true);
            if (empty($country)){
                $country = ApplicationManager::getInstance($this->getServiceLocator())->getCountryByISOCode(self::DEFAULT_COUNTRY_ISO_CODE,true);
            }

            $this->country = isset($country[0]) ? $country[0] : null;
        }

        return $this->country;

    }

    private function getUserLanguage()
    {
        $language = self::DEFAULT_LANGUAGE_ID;
        $country = $this->getUserCountry();
        if (!empty($country['language']['id'])){
            $language = $country['language']['id'];
        }

        return $language;
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
        $country = $this->getUserCountry();
        return !empty($country['id']) ? $country['id'] : self::DEFAULT_COUNTRY_ID;
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
                'value' => $this->getUserLanguage()
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