<?php

namespace Admin\Form;

use Neoco\Form\RegionalisedForm;

class FooterPageForm extends RegionalisedForm
{

    protected $type;

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function __construct($regionFieldsets, $type = 'form-type') {

        parent::__construct($regionFieldsets);
        $this->setAttribute('method', 'post');
        $this->setType($type);

        $this->add(array(
            'name' => 'type',
            'type'  => 'hidden',
            'attributes' => array(
                'value' => $this->getType()
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }

    protected function initFormByObject($dataObject){}
}