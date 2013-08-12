<?php

namespace Custom\Model\DAOs;

use Application\Model\DAOs\UserDAO;
use Doctrine\ORM\Query\ResultSetMapping;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class CustomUserDAO extends UserDAO {

    /**
     * @var CustomUserDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CustomUserDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CustomUserDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getExportUsersData() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('date', 'date', 'datetime');
        $rsm->addScalarResult('title', 'title');
        $rsm->addScalarResult('first_name', 'first_name');
        $rsm->addScalarResult('last_name', 'last_name');
        $rsm->addScalarResult('birthday', 'birthday', 'date');
        $rsm->addScalarResult('country', 'country');
        $rsm->addScalarResult('term1','term1', 'string');
        $rsm->addScalarResult('term2','term2', 'string');
        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT u.email, u.date, u.title, u.first_name, u.last_name, u.birthday, c.name as country,
            (CASE u.term1
                WHEN 1 THEN "Y"
            ELSE "N" END) term1,
            (CASE u.term2
                WHEN 1 THEN "Y"
            ELSE "N" END) term2
            FROM `user` u
            INNER JOIN country c ON c.id = u.country_id', $rsm);
        return $query->getArrayResult();
    }

    /**
     * @param $nextMatchId
     * @param $secondMatchId
     * @param $prevMatchId
     * @param $globalLeagueId
     * @return array
     */
    public function getMaillistData($nextMatchId, $secondMatchId, $prevMatchId, $globalLeagueId) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u.id, u.email')
            ->from($this->getRepositoryName(), 'u')
            ->orderBy('u.id', 'DESC');
        if ($nextMatchId !== null) {
            $qb->leftJoin('u.predictions', 'p', Expr\Join::WITH, 'p.match = ' . $nextMatchId);
            $qb->addSelect('p.homeTeamScore hs1, p.awayTeamScore as1');
        }
        if ($secondMatchId !== null) {
            $qb->leftJoin('u.predictions', 'p2', Expr\Join::WITH, 'p2.match = ' . $secondMatchId);
            $qb->addSelect('p2.homeTeamScore hs2, p2.awayTeamScore as2');
        }
        if ($prevMatchId !== null) {
            $qb->leftJoin('u.predictions', 'p3', Expr\Join::WITH, 'p3.match = ' . $prevMatchId);
            $qb->addSelect('p3.homeTeamScore hs_p1, p3.awayTeamScore as_p1');
        }
        if ($globalLeagueId !== null) {
            $qb->leftJoin('u.leagueUsers', 'lu', Expr\Join::WITH, 'lu.league = ' . $globalLeagueId);
            $qb->addSelect('lu.accuracy acy, lu.points pts, lu.place pl');
        }
        return $this->getQuery($qb, true)->getArrayResult();
    }

}
