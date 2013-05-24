<?php

namespace Admin\Form;

use Zend\Form\Form;
use Admin\Form\Filter\LanguageFormFilter;

class LanguageForm extends Form {

    protected $countries = array();
    public function __construct($countries = array()) {
        parent::__construct('language');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-vertical');
        $this->setCountries($countries);
        $languageFilter = new LanguageFormFilter();
        $this->setInputFilter($languageFilter->getInputFilter());
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'chosen span6',
                'tabindex' => 6,
                'data-placeholder' => 'Choose country',
            ),
            'name' => 'countries',
            'options' => array(
                'label' => 'Language Countries',
                'value_options' => $this->getCountries(),

            ),

        ));
        $this->add(array(
            'name' => 'strings',
            'options' => array(
                'label' => 'Strings',
            ),
            'attributes' => array(
                'class' => '',
                'type' => 'text'
            )
        ));

        $this->add(array(
            'name' => 'language_code',
            'options' => array(
                'label' => 'Code',
            ),
            'attributes' => array(
                'class' => '',
                'type' => 'text'
            )
        ));
        $this->add(array(
            'name' => 'display_name',
            'options' => array(
                'label' => 'Display Name',
            ),
            'attributes' => array(
                'class' => '',
                'type' => 'text'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn blue'
            ),
        ));
    }

    public function setCountries($countries)
    {
        $this->countries = $countries;
        return $this;
    }

    public function getCountries()
    {
        return $this->countries;
    }
}