<?php

namespace Admin\View\Helpers;

use \Zend\Mvc\Controller\Plugin\FlashMessenger;

class LanguageFieldsetsRenderer extends FieldsetsRenderer
{
    public function getName($languageFieldset) {
        $language = $languageFieldset->getLanguage();
        return $language['displayName'];
    }
}