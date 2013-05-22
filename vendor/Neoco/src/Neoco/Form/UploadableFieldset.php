<?php

namespace Neoco\Form;

use \Zend\Form\Fieldset;

abstract class UploadableFieldset extends Fieldset implements \Zend\InputFilter\InputFilterProviderInterface {

    public function getInputFilterSpecification($inputSpec = array()) {
        foreach ($this->getElements() as $element) {
            if ($element->getAttribute('isImage')) {
                $imageData = $element->getValue();
                if ($imageData['stored'] == 1)
                    $validators = array('required' => false);
                else
                    $validators['validators'] = array(array('name' => 'fileisimage'));
                $inputSpec[$element->getName()] = $validators;
            }
        }
        return $inputSpec;
    }

}