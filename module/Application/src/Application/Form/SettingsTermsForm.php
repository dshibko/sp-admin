<?php
namespace Application\Form;

use Application\Manager\ContentManager;
use Application\Model\Entities\User;
use Neoco\Form\TermsForm;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Manager\ApplicationManager;


class SettingsTermsForm extends TermsForm {
    protected $type;

    protected $serviceLocator;


    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
    public function __construct($type = 'change_terms', ServiceLocatorInterface $serviceLocator, $terms = array()) {
        parent::__construct();
        $this->setAttribute('method', 'post')->setAttribute('id', 'settings-change-terms');
        $this->setType($type)->setServiceLocator($serviceLocator)->setTerms($terms);
        //Terms
        $terms = $this->getTerms();
        if (!empty($terms)){
            ContentManager::getInstance($this->getServiceLocator())->addTermsToForm($this, $terms);
        }
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

    public function initForm(User $user)
    {
        $term1 = $user->getTerm1();
        $term2 = $user->getTerm2();
        if (!is_null($term1)){
            $termInput1 = $this->get(ContentManager::TERMS_FIELDSET_NAME)->get('term1');
            if ($term1){
                $termInput1->setAttribute('checked','checked');
            }else{
                $termInput1->setAttribute('checked','');
            }
        }
        if (!is_null($term2)){
            $termInput2 = $this->get(ContentManager::TERMS_FIELDSET_NAME)->get('term2');
            if ($term2){
                $termInput2->setAttribute('checked','checked');
            }else{
                $termInput2->setAttribute('checked','');
            }
        }
    }
}