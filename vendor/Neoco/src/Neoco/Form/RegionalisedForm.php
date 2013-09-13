<?php

namespace Neoco\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

abstract class RegionalisedForm extends Form {

    public function __construct($regionFieldsets, $name = null) {
        parent::__construct($name);
        foreach ($regionFieldsets as $regionFieldset)
            $this->add($regionFieldset);
    }

    public function handleErrorMessages($messages, FlashMessenger $messenger, $parentEl = null, $prefix = '') {
        if ($parentEl === null) $parentEl = $this;
        foreach ($messages as $name => $message)
            if (is_string($message))
                $messenger->addErrorMessage($message);
            else {
                $targetEl = $parentEl->get($name);
                if ($targetEl instanceof Fieldset)
                    $this->handleErrorMessages($message, $messenger, $targetEl, $prefix . $targetEl->getName() . ", ");
                else if ($targetEl instanceof Element)
                    $messenger->addErrorMessage($prefix . $targetEl->getLabel() . ": " . implode(", ", $message));
            }
    }

    public function initForm($dataObject) {
        $this->initFormByObject($dataObject);
        foreach ($this->getFieldsets() as $fieldset)
            if ($fieldset instanceof FieldsetObjectInterface)
                $fieldset->initFieldsetByObject($dataObject);
    }

    abstract protected function initFormByObject($dataObject);

    protected function setElementValue($elementName, $value) {
        if ($this->has($elementName)) {
            $el = $this->get($elementName);
            $elValue = $el->getValue();
            if (empty($elValue))
                $el->setValue($value);
        }
    }

}