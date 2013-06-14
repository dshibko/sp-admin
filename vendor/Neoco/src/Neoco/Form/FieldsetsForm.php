<?php

namespace Neoco\Form;

use Zend\Form\Form;

abstract class FieldsetsForm extends Form {

    public function __construct(array $fieldsets, $name = null) {
        parent::__construct($name);
        foreach ($fieldsets as $fieldset){
            $this->add($fieldset);
        }
    }

    public function handleErrorMessages($messages, $messenger) {
        foreach ($messages as $name => $message) {
            if (is_array($message)) {
                $isInFieldset = false;
                foreach ($this->getFieldsets() as $fieldset) {
                    if ($fieldset->getName() == $name) {
                        $isInFieldset = true;
                        foreach ($message as $k => $aMessage) {
                            $messenger->addErrorMessage(str_replace("_", " ", $fieldset->getName()) . ", " .
                                $fieldset->get($k)->getLabel() . ": " . current($aMessage));
                        }
                        break;
                    }
                }
                if (!$isInFieldset)
                    $messenger->addErrorMessage($this->get($name)->getLabel() . ": " .
                        current($message));
            } else if (is_string($message))
                $messenger->addErrorMessage($message);
        }
    }

    public function initForm($dataObject) {
        $this->initFormByObject($dataObject);
        foreach ($this->getFieldsets() as $fieldset){
            if ($fieldset instanceof \Neoco\Form\FieldsetObjectInterface){
                $fieldset->initFieldsetByObject($dataObject);
            }
        }
    }

    abstract protected function initFormByObject($dataObject);
}