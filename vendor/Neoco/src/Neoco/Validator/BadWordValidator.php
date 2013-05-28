<?php
namespace Neoco\Validator;

use Zend\Validator\AbstractValidator;
//TODO move to vendor/neoco
class BadWordValidator  extends AbstractValidator
{
    const BAD_WORDS = 'badWords';

    protected $messageTemplates = array(
        self::BAD_WORDS => "Input contains bad word"
    );

    protected $options = array(
        'words' => array()
    );

    //TODO get words from config
    protected $badWords = array(
        'sex', 'dildo', 'gay', 'pussy', 'anal',
        'gangbang', 'ass', 'shit',
        'blowjob', 'boobs',
        'nude','cock', 'fuck',
        'dick', 'breast', 'tits', 'suck',
        'virgin', 'oral', 'lesb'
    );

    public function getBadWords()
    {
        return $this->badWords;
    }

    public function setBadWords($badWords)
    {
        $this->badWords = $badWords;
        return $this;
    }

    public function isValid($value){
        $this->setValue($value);
        $value = strtolower($value);
        $badWords = (array)$this->getOption('words');
        $badWords = !empty($badWords) ? $badWords : $this->getBadWords();

        foreach($badWords as $badWord){
            if (strpos($value, $badWord) !== false){
                $this->error(self::BAD_WORDS);
                return false;
            }
        }

        return true;
    }
}
