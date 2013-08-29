<?php

namespace Application\Model\DAOs;

use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;


class MessageDAO extends AbstractDAO {
    /**
     * @var MessageDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MessageDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new MessageDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Message';
    }

    /**
     * @param $userId
     * @param bool $unread
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getUserMessages($userId, $orderByDate = false, $unread = true, $hydrate = false, $skipCache = false) {
        $now = new \DateTime();
        $nowSub7D = $now->sub(new \DateInterval('P7D'));
        $query = $this->getEntityManager()->createQuery('
            SELECT
                m
            FROM
               '.$this->getRepositoryName().' as m
            WHERE m.user = ' . $userId .'
            AND m.date >= :date
            AND m.wasViewed = ' . ($unread === true ? 0 : 1) . '
            ' . ($orderByDate !== false ? " ORDER BY m.date ".$orderByDate : "")
        );
        $query->setParameter('date', $nowSub7D);
        return $this->prepareQuery($query, array(MatchDAO::getInstance($this->getServiceLocator())->getRepositoryName()), $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }
}