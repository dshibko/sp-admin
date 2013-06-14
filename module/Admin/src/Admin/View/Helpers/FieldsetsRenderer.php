<?php

namespace Admin\View\Helpers;

use Zend\View\Helper\AbstractHelper;
use \Zend\Mvc\Controller\Plugin\FlashMessenger;

class FieldsetsRenderer extends AbstractHelper
{

    protected $translator;

    /**
     * @param $fieldsets
     * @param bool $add
     * @param string $uniqueName
     * @return string
     */
    public function __invoke($fieldsets, $add = true, $uniqueName = '')
    {
        $html = '<div class="row-fluid form-vertical">
               <div class="span12">
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">';
        $i = 0;
        foreach ($fieldsets as $fieldset) {
            $data = $fieldset->getData();
            $html .= '<li' . ($i == 0 ? ' class="active"' : '') . '><a href="#tab_' . $uniqueName . (++$i) . '" data-toggle="tab">' . $data['displayName'] . '</a></li>';
        }
        $html .= '</ul><div class="tab-content">';
        $i = 0;
        foreach ($fieldsets as $fieldset) {
            $html .= '<div class="tab-pane' . ($i == 0 ? ' active' : '') . '" id="tab_' . $uniqueName . (++$i) . '">';
            $html .= $this->renderFieldset($fieldset, $add);
            $html .= '</div>';
        }
        $html .= '</div></div></div></div>';
        return $html;
    }

    private function renderFieldset($fieldset, $add = true) {
        $html = '';
        foreach ($fieldset->getElements() as $element) {
            $type = $element->getAttribute('type');
            $hint = $element->getAttribute('hint');
            $maxlength = $element->getAttribute('maxlength');
            $html .= '<div class="control-group clearfix">
    <label class="control-label">' . $this->translator->translate($element->getLabel()) . '</label>
    <div class="controls">';
            switch($type) {
                case 'textarea':
                    $html .= '<div class="input-prepend"><textarea class="span12 wysihtml5 m-wrap" rows="6" name="' . $element->getAttribute('name') . '" '.(!empty($maxlength) ? 'maxlength="'.$maxlength.'"' : '').'>' . $element->getValue() . '</textarea></div>';
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
                case 'select' :
                    $html .= $this->getView()->formSelect($element);
                    break;
                default:
                    $html .= '<div class="input-prepend"><span class="add-on"><i class="icon-tag"></i></span><input class="m-wrap medium" type="' . $type . '" name="' . $element->getAttribute('name') . '" value="' . $element->getValue() . '" '.(!empty($maxlength) ? 'maxlength="'.$maxlength.'"' : '').'/></div>';
            }
            if (!empty($hint)){
               $html .= '<span class="help-inline">'.$hint.'</span>';
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