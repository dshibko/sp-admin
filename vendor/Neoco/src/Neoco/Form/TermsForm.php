<?php

namespace Neoco\Form;

use Application\Manager\ContentManager;
use \Zend\Form\Form;


abstract class TermsForm extends Form {
    protected $terms = array();

    public function getTerms()
    {
        return $this->terms;
    }

    public function setTerms($terms)
    {
        $this->terms = $terms;
        return $this;
    }

    public function getTermsFieldset()
    {
        $fieldsets = $this->getFieldsets();
        $terms = null;
        if (!empty($fieldsets[ContentManager::TERMS_FIELDSET_NAME])){
            $terms = $fieldsets[ContentManager::TERMS_FIELDSET_NAME];

        }
        return $terms;
    }
}