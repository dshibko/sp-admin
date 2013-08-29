<?php

namespace Admin\Form;

use Application\Model\Entities\Competition;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class CompetitionForm extends Form implements InputFilterProviderInterface
{

    public function __construct($name = 'form') {

        parent::__construct($name);
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'attributes' => array(
                'disabled' => true,
            ),
            'options' => array(
                'label' => 'Name'
            ),
        ));

        $this->add(array(
            'name' => 'logo',
            'type' => 'file',
            'attributes' => array(
                'isImage' => true,
                'required' => true,
            ),
            'options' => array(
                'label' => 'Logo'
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
            if ($element->getAttribute('isImage')) {
                $validators = array();
                $imageData = $element->getValue();
                if (is_array($imageData) && array_key_exists('stored', $imageData) && $imageData['stored'] == 1){
                    $validators = array('required' => false);
                }else{
                    $validators['validators'] = array(array('name' => 'fileextension', 'options' => array('extension' => 'jpg,jpeg,gif,png,bmp')));
                    $validators['required'] = true;
                }
                $inputSpec[$element->getName()] = $inputSpec[$element->getName()] != null ?
                    array_merge($inputSpec[$element->getName()], $validators) : $validators;
            }
        }
        return $inputSpec;
    }

    public function initFormByObject(Competition $competition){
        $this->get('name')->setValue($competition->getDisplayName());
        $this->get('logo')->setValue($competition->getLogoPath());
    }
}