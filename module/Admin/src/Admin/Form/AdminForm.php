<?php

namespace Admin\Form;

use Application\Model\Entities\Role;
use Zend\Form\Form;
use \Admin\Form\Filter\AdminFormFilter;

class AdminForm extends Form {

    public function __construct($id = 'admin-form') {
        parent::__construct($id);
        $this->setAttribute('method', 'post');
        //First name
        $this->add(array(
            'name' => 'first_name',
            'type'  => 'text',
            'attributes' => array(
                'maxlength'=> AdminFormFilter::NAME_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ));

        //Last Name
        $this->add(array(
            'name' => 'last_name',
            'type'  => 'text',
            'attributes' => array(
                'maxlength'=> AdminFormFilter::NAME_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));
        //Display Name
        $this->add(array(
            'name' => 'display_name',
            'type'  => 'text',
            'attributes' => array(
                'maxlength'=> AdminFormFilter::DISPLAY_NAME_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Display Name',
            ),
        ));
        //Permissions
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(

            ),
            'name' => 'permissions',
            'options' => array(
                'label' => 'Permissions',
                'value_options' => array(
                     Role::SUPER_ADMIN => Role::SUPER_ADMIN
                )

            ),
        ));

        //Email
        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
            'attributes' => array(
                'maxlength'=> AdminFormFilter::EMAIL_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Email',
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

    public function initForm(\Application\Model\Entities\User $user)
    {
        $this->get('first_name')->setValue($user->getFirstName());
        $this->get('last_name')->setValue($user->getLastName());
        $this->get('display_name')->setValue($user->getDisplayName());
        $this->get('email')->setValue($user->getEmail());
    }
}