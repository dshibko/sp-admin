<?php

namespace Admin\View\Helpers;

use \Zend\Mvc\Controller\Plugin\FlashMessenger;

class RegionFieldsetsRenderer extends FieldsetsRenderer
{
    public function getName($regionFieldset) {
        return $regionFieldset->getRegion();
    }
}