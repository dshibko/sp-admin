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
                    $sizes = array();
                    $minWidth = $element->getAttribute('minWidth');
                    if (!empty($minWidth))
                        $sizes['minWidth'] = $minWidth;
                    $minHeight = $element->getAttribute('minHeight');
                    if (!empty($minHeight))
                        $sizes['minHeight'] = $minHeight;
                    $width = $element->getAttribute('width');
                    if (!empty($width))
                        $sizes['width'] = $width;
                    $height = $element->getAttribute('height');
                    if (!empty($height))
                        $sizes['height'] = $height;
                    if (!empty($sizes))
                        $validators['validators'][] = array('name' => 'fileimagesize', 'options' => $sizes);
                }
                $inputSpec[$element->getName()] = $validators;
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
        }

        return $inputSpec;
    }

}