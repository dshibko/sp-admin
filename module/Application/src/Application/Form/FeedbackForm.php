<?php

namespace Application\Form;

use Zend\Form\Form;
use \Application\Form\Filter\FeedbackFormFilter;

class FeedbackForm extends Form {

    public function __construct($name = null) {
        parent::__construct('feedback');

        $this->setInputFilter(new FeedbackFormFilter());
        $this->setAttribute('method', 'post');

        //Name
        $this->add(array(
            'name' => 'name',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'require',
                'maxlength' => FeedbackFormFilter::NAME_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        //Email
        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required email'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        //Query
        $this->add(array(
            'name' => 'query',
            'type'  => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'required',
                'maxlength' => FeedbackFormFilter::QUERY_MAX_LENGTH,
                'style'=> 'height:150px'
            ),
            'options' => array(
                'label' => 'Message',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Send',
                'id' => 'submitbutton',
            ),
        ));
    }
}