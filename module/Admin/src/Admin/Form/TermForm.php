<?php

namespace Admin\Form;

use Admin\Form\Filter\TermFormFilter;
use Neoco\Form\RegionalisedForm;

class TermForm extends RegionalisedForm
{

    public function __construct($fieldsets) {

        parent::__construct($fieldsets);
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new TermFormFilter());
        //Required
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'required',
            'options' => array(
                'label' => 'Required',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
            'attributes' => array(
                'class' => 'form-checkbox',
                'required' => false
            )
        ));

        //Checked
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'checked',
            'options' => array(
                'label' => 'Checked',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
            'attributes' => array(
                'class' => 'form-checkbox',
                'required' => false
            )
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

    protected function initFormByObject($term){
        if (!empty($term)){
            $this->get('checked')->setValue($term->getIsChecked());
            $this->get('required')->setValue($term->getIsRequired());
        }
    }
}