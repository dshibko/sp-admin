<?php

namespace Application\Manager;


use Application\Model\DAOs\MatchReportMessageDAO;
use Application\Model\DAOs\MessageDAO;
use Application\Model\DAOs\PredictionDAO;
use Application\Model\DAOs\UserDAO;
use Application\Model\Entities\MatchReportMessage;
use Application\Model\Entities\Message;
use Doctrine\Tests\ORM\Mapping\User;
use Neoco\Manager\BasicManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class MessageManager extends BasicManager {
    /**
     * @var MessageManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MessageManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new MessageManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param $messageId
     */
    public function setMessageViewed($messageId) {
        $messageDAO = MessageDAO::getInstance($this->getServiceLocator());
        $message = $messageDAO->findOneById($messageId);
        $message->setWasViewed(true);
        $messageDAO->save($message);
    }

    /**
     * @param $userId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getUnviewedMessages($userId, $orderByDate = false, $hydrate = false, $skipCache = false) {
        $messageDAO = MessageDAO::getInstance($this->getServiceLocator());

        $unreadMessages = $messageDAO->getUserMessages($userId, $orderByDate, true, $hydrate, $skipCache);

        return $unreadMessages;
    }

    /**
     * @param $userId
     * @param $predictionId
     */
    public function addMatchReportMessage($userId, $predictionId) {
        $messageDAO = MessageDAO::getInstance($this->getServiceLocator());

        $message = new Message();
        $message->setDate(new \DateTime());
        $message->setWasViewed(false);
        $message->setMessageType(Message::MATCH_REPORT_TYPE);
        $message->setUser(UserDAO::getInstance($this->getServiceLocator())->findOneById($userId));

        $matchReportMessage = new MatchReportMessage();
        $matchReportMessage->setMessage($message);
        $matchReportMessage->setPrediction(PredictionDAO::getInstance($this->getServiceLocator())->findOneById($predictionId));

        $message->setMatchReportMessage($matchReportMessage);

        $messageDAO->save($message);
    }

    public function saveMessage($message, $flush = true, $clearCache = true) {
        MessageDAO::getInstance($this->getServiceLocator())->save($message, $flush, $clearCache);
    }

    public function flush() {
        MessageDAO::getInstance($this->getServiceLocator())->flush();
    }

    public function clearCache() {
        MessageDAO::getInstance($this->getServiceLocator())->clearCache();
    }
}