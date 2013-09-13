<?php
namespace Neoco\Validator;

use Zend\Validator\AbstractValidator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Manager\SettingsManager;

class BadWordValidator  extends AbstractValidator implements ServiceLocatorAwareInterface
{
    const BAD_WORDS = 'badWords';
    protected $serviceLocator;


    public function getConfigBadWords()
    {
        $words = array();
        $settingsManager = SettingsManager::getInstance($this->getServiceLocator());
        $badWords = $settingsManager->getSetting(SettingsManager::BAD_WORDS);
        if (!empty($badWords)){
            $badWords = strtolower($badWords);
            $words = explode(",", $badWords);
        }
        return $words;

    }
    public function getServiceLocator ()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    protected $messageTemplates = array(
        self::BAD_WORDS => "Input contains bad word"
    );

    protected $options = array(
        'words' => array()
    );


    public function isValid($value){
        $this->setValue($value);
        $value = strtolower(trim($value));
        $badWords = $this->getConfigBadWords();

        foreach($badWords as $badWord){
            if (strpos($value, trim($badWord)) !== false){
                $this->error(self::BAD_WORDS);
                return false;
            }
        }
        return true;
    }
}
