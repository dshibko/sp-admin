<?php

namespace Admin\Form;

use \Neoco\Form\RegionalisedForm;


class FeaturedPlayerForm extends RegionalisedForm {

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

    public function __construct($languageFieldsets, $type = 'featured_player') {

        parent::__construct($languageFieldsets, 'featured-player');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
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

    /**
     * @param \Application\Model\Entities\Match $match
     */
    protected function initFormByObject($match) {}
}