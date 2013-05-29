<?php
namespace Application\Form;

use Zend\Form\Form;
use Application\Form\Filter\RegistrationFilter;
use Application\Manager\ApplicationManager;

class SettingsAvatarForm extends Form {
    protected $type;


    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
    public function __construct($type = 'change_avatar') {
        parent::__construct();
        $this->setAttribute('method', 'post')->setAttribute('id', 'settings-change-avatar');
        $this->setType($type);

        //Avatar
        $this->add(array(
            'name' => 'avatar',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Upload a new avatar',
            ),
        ));
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
                'value' => 'Save changes',
                'id' => 'submitbutton',
            ),
        ));
    }
}