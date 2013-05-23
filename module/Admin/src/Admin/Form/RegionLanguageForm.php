<?php

namespace Admin\Form;

use Zend\Form\Form;

class RegionLanguageForm extends Form implements \Zend\InputFilter\InputFilterProviderInterface {

    public function __construct($name = null) {
        parent::__construct('region-language');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-vertical');

        $this->add(array(
            'name' => 'language',
            'type'  => 'select',
            'options' => array(
                'label' => 'Default Language',
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4 m-wrap',
                'data-placeholder' => 'Default Language',
            ),
        ));

        $this->add(array(
            'name' => 'region',
            'type'  => 'select',
            'options' => array(
                'label' => 'Default Region',
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4 m-wrap',
                'data-placeholder' => 'Default Region',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }

    public function getInputFilterSpecification($inputSpec = array()) {
        foreach ($this->getElements() as $element) {
            if ($element->getAttribute('required'))
               $inputSpec[$element->getName()]['required'] = true;
        }
        return $inputSpec;
    }

}