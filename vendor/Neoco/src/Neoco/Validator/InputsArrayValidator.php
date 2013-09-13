<?php
namespace Neoco\Validator;

use Zend\Validator\AbstractValidator;

class InputsArrayValidator  extends AbstractValidator
{
    const EMPTY_VALUES = 'emptyValues';

    protected $messageTemplates = array(
        self::EMPTY_VALUES=> "Array of inputs contains empty values"
    );

    protected $options = array();



    public function isValid($data){
        $this->setValue($data);
        if (empty($data) || !is_array($data)){
           $this->error(self::EMPTY_VALUES);
           return false;
        }
        foreach($data as $value){
            if (empty($value)){
                $this->error(self::EMPTY_VALUES);
                return false;
            }
        }
        return true;
    }
}
