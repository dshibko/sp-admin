<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Doctrine\ORM\Query\ResultSetMapping;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PredictionPlayerDAO extends AbstractDAO {

    /**
     * @var PredictionPlayerDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PredictionPlayerDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PredictionPlayerDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\PredictionPlayer';
    }

    public function getMostPopularScorer($matchId, $teamId) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('display_name','display_name');
        $rsm->addScalarResult('background_image_path','background_image_path');
        $query = $this->getEntityManager()
            ->createNativeQuery("
                SELECT `pl`.`display_name`, `pl`.`background_image_path`
                FROM  `prediction_player` AS pp
                INNER JOIN `prediction` AS p ON `p`.`id` = `pp`.`prediction_id`
                INNER JOIN `player` AS pl ON `pp`.`player_id` = `pl`.`id`
                WHERE `p`.`match_id` = ". $matchId ."
                    AND `pp`.`team_id` = ". $teamId ."
                GROUP BY `pp`.`player_id`
                ORDER BY COUNT( `pp`.`id` ) DESC
                LIMIT 1
            ", $rsm);
        return $this->prepareQuery($query, array($this->getRepositoryName(),
                                                PredictionDAO::getInstance($this->getServiceLocator())->getRepositoryName(),
                                                PlayerDAO::getInstance($this->getServiceLocator())->getRepositoryName()))
                    ->getSingleResult();
    }

}
