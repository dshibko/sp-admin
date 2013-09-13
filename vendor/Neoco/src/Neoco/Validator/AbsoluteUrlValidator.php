<?php
namespace Neoco\Validator;

use Zend\Validator\AbstractValidator;

class AbsoluteUrlValidator  extends AbstractValidator
{
    const INCORRECT_ABSOLUTE_URL = 'incorrectAbsoluteUrl';
    const ABSOLUTE_URL_PATTERN = '/^(http(?:s)?\:\/\/[a-zA-Z0-9\-\+]+(?:\.[a-zA-Z0-9\-\+]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-\+]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/';    
    protected $messageTemplates = array(
        self::INCORRECT_ABSOLUTE_URL=> "Incorrect Absolute Url"
    );

    protected $options = array();

    public function isValid($value){

        if (!preg_match(self::ABSOLUTE_URL_PATTERN, $value)){
           $this->error(self::INCORRECT_ABSOLUTE_URL);
           return false;
        }
        return true;
    }
}
