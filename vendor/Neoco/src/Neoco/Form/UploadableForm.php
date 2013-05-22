<?php

namespace Neoco\Form;

use \Zend\Form\Form;
use \Zend\Form\Fieldset;

abstract class UploadableForm extends Form implements \Zend\InputFilter\InputFilterProviderInterface {

    public function getInputFilterSpecification($inputSpec = array()) {
        foreach ($this->getElements() as $element) {
            if ($element->getAttribute('required'))
                $inputSpec[$element->getName()]['required'] = true;
            if ($element->getAttribute('isImage')) {
                $imageData = $element->getValue();
                if (is_array($imageData) && $imageData['stored'] == 1)
                    $validators = array('required' => false);
                else {
                    $validators['validators'] = array(
                        array('name' => 'fileisimage')
                    );
                    $minWidth = $element->getAttribute('minWidth');
                    if (!empty($minWidth))
                        $validators['validators'][] = array('name' => 'fileimagesize', 'options' => array(
                            'minWidth' => $minWidth
                        ));
                }
                $inputSpec[$element->getName()] = $validators;
            }
        }
        return $inputSpec;
    }

}