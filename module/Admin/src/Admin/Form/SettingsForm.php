<?php

namespace Admin\Form;

use Zend\Form\Form;
use Admin\Form\Filter\SettingsFormFilter;

class SettingsForm extends Form implements \Zend\InputFilter\InputFilterProviderInterface {

    public function __construct($name = null) {
        parent::__construct('settings');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-vertical');
        $this->setAttribute('novalidate', true);
        $this->setInputFilter(new SettingsFormFilter());

        $this->add(array(
            'name' => 'language',
            'type'  => 'select',
            'options' => array(
                'label' => 'Default Language',
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4 m-wrap',
                'data-placeholder' => 'Default Language',
            ),
        ));

        $this->add(array(
            'name' => 'region',
            'type'  => 'select',
            'options' => array(
                'label' => 'Default Region',
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4 m-wrap',
                'data-placeholder' => 'Default Region',
            ),
        ));

        $this->add(array(
            'name' => 'bad-words',
            'type'  => 'textarea',
            'options' => array(
                'label' => 'Bad word filter',
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4',
                'hint' => 'Bad word filter words (for users display name). Separate by comma, f.e word1,word2,word3',
            ),
        ));

        $this->add(array(
            'name' => 'ahead-predictions-days',
            'type'  => 'text',
            'options' => array(
                'label' => 'Ahead predictions days',
            ),
            'attributes' => array(
                'required' => true,
                'hint' => 'Number of matches/game weeks ahead a user can predicted',
                'class' => 'span1',
            ),
        ));

        $this->add(array(
            'name' => 'help-and-support-email',
            'type'  => 'text',
            'options' => array(
                'label' => 'Help and Support Email',
            ),
            'attributes' => array(
                'required' => true,
                'hint' => 'Email address for mail from Help And Support form',
            ),
        ));

        $this->add(array(
            'name' => 'main-site-link',
            'type'  => 'text',
            'options' => array(
                'label' => 'Main site link',
            ),
            'attributes' => array(
                'required' => true,
                'hint' => 'Source for "Visit our main site" link',
            ),
        ));
        $this->add(array(
            'name' => 'ga-account-id',
            'type'  => 'text',
            'options' => array(
                'label' => 'Google Analytics Id',
            ),
            'attributes' => array(
                'required' => true,
                'hint' => 'Like this "UA-XXXXX-Y"',
            ),
        ));
        $this->add(array(
            'name' => 'send-welcome-email',
            'type'  => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Send Welcome Email',
            ),
            'attributes' => array(
                'required' => true,
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

    public function getInputFilterSpecification($inputSpec = array()) {
        foreach ($this->getElements() as $element) {
            if ($element->getAttribute('required'))
               $inputSpec[$element->getName()]['required'] = true;
        }
        return $inputSpec;
    }

}