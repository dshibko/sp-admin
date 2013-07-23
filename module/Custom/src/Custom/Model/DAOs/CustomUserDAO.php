<?php

namespace Custom\Model\DAOs;

use Application\Model\DAOs\UserDAO;
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
//            $qb->join('u.predictions', 'p', Expr\Join::WITH, 'p.match = ' . $nextMatchId);
            $qb->leftJoin('u.predictions', 'p', Expr\Join::WITH, 'p.match = ' . $nextMatchId);
            $qb->addSelect('p.homeTeamScore hs1, p.awayTeamScore as1');
        } else
            $qb->addSelect('null hs1, null as1');
        if ($secondMatchId !== null) {
//            $qb->join('u.predictions', 'p2', Expr\Join::WITH, 'p2.match = ' . $secondMatchId);
            $qb->leftJoin('u.predictions', 'p2', Expr\Join::WITH, 'p2.match = ' . $secondMatchId);
            $qb->addSelect('p2.homeTeamScore hs2, p2.awayTeamScore as2');
        } else
            $qb->addSelect('null hs2, null as2');
        if ($prevMatchId !== null) {
//            $qb->join('u.predictions', 'p3', Expr\Join::WITH, 'p3.match = ' . $prevMatchId);
            $qb->leftJoin('u.predictions', 'p3', Expr\Join::WITH, 'p3.match = ' . $prevMatchId);
            $qb->addSelect('p3.homeTeamScore hs_p1, p3.awayTeamScore as_p1');
        } else
            $qb->addSelect('null hs_p1, null as_p1');
        if ($globalLeagueId !== null) {
            $qb->leftJoin('u.leagueUsers', 'lu', Expr\Join::WITH, 'lu.league = ' . $globalLeagueId);
            $qb->addSelect('lu.accuracy acy, lu.points pts, lu.place pl');
        } else
            $qb->addSelect('null acy, null pts, null pl');
        return $this->getQuery($qb, true)->getArrayResult();
    }

}
