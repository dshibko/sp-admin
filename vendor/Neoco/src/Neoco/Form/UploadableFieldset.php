<?php

namespace Neoco\Form;

use \Zend\Form\Fieldset;

abstract class UploadableFieldset extends Fieldset implements \Zend\InputFilter\InputFilterProviderInterface {

    public function getInputFilterSpecification($inputSpec = array()) {
        $requiredFieldgroups = array();
        $requiredElements = array();
        foreach ($this->getElements() as $element) {
            if ($element->getAttribute('fieldgroup')){
                $fieldgroup = $element->getAttribute('fieldgroup');
                $value = $element->getValue();
                //if image but not uploaded and not stored - it is not required
                if ($element->getAttribute('isImage')){
                    if (empty($value['stored']) && $value['error'] == UPLOAD_ERR_NO_FILE){
                        $value = false;
                    }
                }
                if (!empty($value)){
                    if (!in_array($fieldgroup['name'], $requiredFieldgroups)){
                        $requiredFieldgroups[] = $fieldgroup['name'];
                    }
                }
                if (in_array($fieldgroup['name'], $requiredFieldgroups)){
                    $requiredElements[] = $element;
                }

            }
            if ($element->getAttribute('isImage')) {
                $imageData = $element->getValue();
                if ($imageData['stored'] == 1){
                    $validators = array('required' => false);
                }else{
                    $validators['validators'] = array(array('name' => 'fileisimage'));
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

        if (!empty($requiredElements)){
            foreach($requiredElements as $element){
                $inputSpec[$element->getName()]['required'] = true;
                if ($element->getAttribute('isImage')) {
                    $imageData = $element->getValue();
                    if ($imageData['stored'] == 1){
                        $validators = array('required' => false);
                    }else{
                        $validators['validators'] = array(array('name' => 'fileisimage'));
                    }
                    $inputSpec[$element->getName()] = array_merge($inputSpec[$element->getName()], $validators);
                }
            }
        }
        return $inputSpec;
    }

}