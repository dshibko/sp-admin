<?php

namespace Neoco\Form;

use \Zend\Form\Fieldset;

abstract class UploadableFieldset extends Fieldset implements \Zend\InputFilter\InputFilterProviderInterface {

    public function getInputFilterSpecification($inputSpec = array()) {
    
        foreach ($this->getElements() as $element) {
            if ($element->getAttribute('isImage')) {
                $imageData = $element->getValue();
                if (is_array($imageData) && array_key_exists('stored', $imageData) && $imageData['stored'] == 1){
                    $validators = array('required' => false);
                }else{
                    $validators['validators'] = array(array('name' => 'fileextension', 'options' => array('extension' => 'jpg,jpeg,gif,png,bmp')));
                }
                $inputSpec[$element->getName()] = array_merge($inputSpec[$element->getName()], $validators);
            }
            if ($element->getAttribute('maxlength')){
                $inputSpec[$element->getName()]['validators'][] = array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => $element->getAttribute('maxlength'),
                    ),
                );
            }
            if ($element->getAttribute('between')){

                $inputSpec[$element->getName()]['validators'][] = array(
                    'name' => 'Zend\Validator\Between',
                    'options' => $element->getAttribute('between')
                );
            }
            if ($element->getAttribute('digits')){
                $inputSpec[$element->getName()]['validators'][] = array(
                    'name' => 'Zend\Validator\Digits',
                );
            }
        }
        return $inputSpec;
    }

}