<?php

namespace Admin\View\Helpers;

use ZfcRbac\Service\Rbac as RbacService;
use Zend\View\Helper\AbstractHelper;
use \Zend\Mvc\Controller\Plugin\FlashMessenger;

class RegionFieldsetsRenderer extends AbstractHelper
{

    protected $translator;

    /**
     * @param $regionFieldsets
     * @param bool $add
     * @return string
     */
    public function __invoke($regionFieldsets, $add = true)
    {
        $html = '<div class="row-fluid form-vertical">
               <div class="span12">
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">';
        $i = 0;
        foreach ($regionFieldsets as $regionFieldset) {
            $region = $regionFieldset->getRegion();
            $html .= '<li' . ($i == 0 ? ' class="active"' : '') . '><a href="#tab_' . (++$i) . '" data-toggle="tab">' . $region['displayName'] . '</a></li>';
        }
        $html .= '</ul><div class="tab-content">';
        $i = 0;
        foreach ($regionFieldsets as $regionFieldset) {
            $html .= '<div class="tab-pane' . ($i == 0 ? ' active' : '') . '" id="tab_' . (++$i) . '">';
            $html .= $this->renderRegionFieldset($regionFieldset, $add);
            $html .= '</div>';
        }
        $html .= '</div></div></div></div>';
        print $html;
    }

    private function renderRegionFieldset($regionFieldset, $add = true) {
        $html = '';
        foreach ($regionFieldset->getElements() as $element) {
            $type = $element->getAttribute('type');
            $html .= '<div class="control-group">
    <label class="control-label">' . $this->translator->translate($element->getLabel()) . '</label>
    <div class="controls">';
            switch($type) {
                case 'textarea':
                    $html .= '<div class="input-prepend"><textarea class="span12 wysihtml5 m-wrap" rows="6" name="' . $element->getAttribute('name') . '">' . $element->getValue() . '</textarea></div>';
                    break;
                case 'file':
                    $image = $add ? '': (is_string($element->getValue()) ? $element->getValue() : '');
                    $html .= '<div class="fileupload fileupload-' . (!empty($image) ? 'exists' : 'new') . '" data-provides="fileupload">
                    <div class="input-append">
                    <div class="uneditable-input">
                    <i class="icon-file fileupload-exists"></i>
                    <span class="fileupload-preview">' . (!empty($image) ? '<input type="hidden" name="' . $element->getAttribute('name') . '[stored]" value="1"/><a href="' . $image . '" target="_blank">' . $this->translator->translate('Open') . '</a>' : '') . '</span>
                    </div>
                    <span class="btn btn-file">
                    <span class="fileupload-new">' . $this->translator->translate('Select file') . '</span>
                    <span class="fileupload-exists">' . $this->translator->translate('Change') . '</span>
                    <input type="file" value="" class="default" name="' . $element->getAttribute('name') . '"/>
                    </span>
                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">' . $this->translator->translate('Remove') . '</a>
                    </div>
                    </div>';
                    break;
                default:
                    $html .= '<div class="input-prepend"><span class="add-on"><i class="icon-tag"></i></span><input class="m-wrap medium" type="' . $type . '" name="' . $element->getAttribute('name') . '" value="' . $element->getValue() . '"/></div>';
            }
        $html .= '</div>
                </div>';
        }
        return $html;
    }


    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

}