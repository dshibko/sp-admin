<?php

namespace Custom\Manager;

use Application\Manager\ApplicationManager;
use Application\Manager\ExportManager;
use Application\Manager\MatchManager;
use Application\Manager\PredictionManager;
use \Application\Model\DAOs\AvatarDAO;
use Application\Model\DAOs\LeagueDAO;
use Application\Model\DAOs\LeagueUserDAO;
use Application\Model\DAOs\UserDAO;
use Custom\Model\DAOs\CustomLeagueDAO;
use Custom\Model\DAOs\CustomMatchDAO;
use Custom\Model\DAOs\CustomUserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class CustomExportManager extends BasicManager {

    /**
     * @var CustomExportManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CustomExportManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CustomExportManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function getMaillistExportContent($toCSV = true) {
        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $customMatchDAO = CustomMatchDAO::getInstance($this->getServiceLocator());
        $customLeagueDAO = CustomLeagueDAO::getInstance($this->getServiceLocator());
        $customUserDAO = CustomUserDAO::getInstance($this->getServiceLocator());
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season !== null) {
            $globalLeague = ApplicationManager::getInstance($this->getServiceLocator())->getGlobalLeague($season);
            $globalLeagueId = $globalLeague->getId();
            $nextMatch = $customMatchDAO->getNextMatch();
            $nextMatchId = $nextMatch != null ? $nextMatch->getId() : null;
            $nextMatchHomeTeamName = $nextMatch != null ? $nextMatch->getHomeTeam()->getDisplayName() : null;
            $nextMatchAwayTeamName = $nextMatch != null ? $nextMatch->getAwayTeam()->getDisplayName() : null;
            $nextMatchCompetitionName = $nextMatch != null ? $nextMatch->getCompetition()->getDisplayName() : null;
            $secondMatch = $customMatchDAO->getSecondMatch();
            $secondMatchId = $secondMatch != null ? $secondMatch->getId() : null;
            $secondMatchHomeTeamName = $secondMatch != null ? $secondMatch->getHomeTeam()->getDisplayName() : null;
            $secondMatchAwayTeamName = $secondMatch != null ? $secondMatch->getAwayTeam()->getDisplayName() : null;
            $secondMatchCompetitionName = $secondMatch != null ? $secondMatch->getCompetition()->getDisplayName() : null;
            $prevMatch = $customMatchDAO->getPrevMatch();
            $prevMatchId = $prevMatch != null ? $prevMatch->getId() : null;
            $usersWithPerfectResult = $prevMatch != null ? $predictionManager->getUsersWithPerfectResult($prevMatch->getId()) : array();
            $usersWithPerfectResultArr = array();
            foreach ($usersWithPerfectResult as $userWithPerfectResult)
                $usersWithPerfectResultArr [] = $userWithPerfectResult['id'];
            $prevMatchHomeTeamName = $prevMatch != null ? $prevMatch->getHomeTeam()->getDisplayName() : null;
            $prevMatchAwayTeamName = $prevMatch != null ? $prevMatch->getAwayTeam()->getDisplayName() : null;
            // todo to refactor
            $prevMatchCompetitionName = $prevMatch != null ? $prevMatch->getCompetition()->getDisplayName() : null;
            $lastMiniLeague = $customLeagueDAO->getLastMiniLeague($season);
            if ($lastMiniLeague != null) {
                $lastMiniLeagueName = $lastMiniLeague->getDisplayName();
                $lastMiniLeagueWinnerAndRunnerUp = $leagueUserDAO->getLeagueTop($lastMiniLeague->getId(), 2);
                if (!empty($lastMiniLeagueWinnerAndRunnerUp)) {
                    $lastMiniLeagueWinnerId = $lastMiniLeagueWinnerAndRunnerUp[0]['userId'];
                    if (count($lastMiniLeagueWinnerAndRunnerUp) > 1)
                        $lastMiniLeagueRunnerUpId = $lastMiniLeagueWinnerAndRunnerUp[1]['userId'];
                    else
                        $lastMiniLeagueRunnerUpId = null;
                } else {
                    $lastMiniLeagueWinnerId = null;
                    $lastMiniLeagueRunnerUpId = null;
                }
            } else
                $lastMiniLeagueName = $lastMiniLeagueWinnerId = $lastMiniLeagueRunnerUpId = null;
        } else {
            $nextMatchId = $secondMatchId = $prevMatchId = $globalLeagueId = $nextMatchHomeTeamName = $nextMatchAwayTeamName = $nextMatchCompetitionName =
            $secondMatchHomeTeamName = $secondMatchAwayTeamName = $secondMatchCompetitionName = $prevMatchHomeTeamName = $prevMatchAwayTeamName =
            $prevMatchCompetitionName = $lastMiniLeagueName = $lastMiniLeagueWinnerId = $lastMiniLeagueRunnerUpId = null;
            $usersWithPerfectResultArr = array();
        }
        $maillistExportContent = $customUserDAO->getMaillistData($nextMatchId, $secondMatchId, $prevMatchId, $globalLeagueId);
        foreach ($maillistExportContent as &$content) {

            $nullables = array('hs1', 'as1', 'hs2', 'as2', 'hs_p1', 'as_p1', 'acy', 'pts', 'pl');
            foreach ($nullables as $nullable)
                if (!array_key_exists($nullable, $content))
                    $content[$nullable] = null;

            $content['hn1'] = $nextMatchHomeTeamName;
            $content['an1'] = $nextMatchAwayTeamName;
            $content['cn1'] = $nextMatchCompetitionName;
            $content['hn2'] = $secondMatchHomeTeamName;
            $content['an2'] = $secondMatchAwayTeamName;
            $content['cn2'] = $secondMatchCompetitionName;
            $content['ps'] = in_array($content['id'], $usersWithPerfectResultArr) ? 1 : 0;
            $content['hn_p1'] = $prevMatchHomeTeamName;
            $content['an_p1'] = $prevMatchAwayTeamName;
            $content['cn_p1'] = $prevMatchCompetitionName;
            $content['w_ml'] = $lastMiniLeagueWinnerId != null && $content['id'] == $lastMiniLeagueWinnerId ? 1 : 0;;
            $content['wt_ml'] = $lastMiniLeagueName;
            $content['r_ml'] = $lastMiniLeagueRunnerUpId != null && $content['id'] == $lastMiniLeagueRunnerUpId ? 1 : 0;;
            $content['rt_ml'] = $lastMiniLeagueName;
        }
        $aliasConfig = array(
            'email' => 'Email',
            'title' => 'Title',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'birthday' => 'DateofBirth',
            'country' => 'County',
            'term1' => 'CFCEmailOptIn',
            'term2' => 'CFC3rdPartyOptIn',
            'date' => 'SPG_EntryDate',
            'hn1' => 'SPG_HomeName1',
            'an1' => 'SPG_AwayName1',
            'hs1' => 'SPG_HomeScore1',
            'as1' => 'SPG_AwayScore1',
            'cn1' => 'SPG_Comp1',
            'hn2' => 'SPG_HomeName2',
            'an2' => 'SPG_AwayName2',
            'hs2' => 'SPG_HomeScore2',
            'as2' => 'SPG_AwayScore2',
            'cn2' => 'SPG_Comp2',
            'acy' => 'SPG_PercentageCorrect',
            'pts' => 'SPG_Points',
            'pl' => 'SPG_GlobalPosition',
            'w_ml' => 'MonthlyLeagueWinner',
            'wt_ml' => 'MonthlyLeagueWinnerTime',
            'r_ml' => 'MonthlyLeagueRunnerUp',
            'rt_ml' => 'MonthlyLeagueRunnerUpTime',
            'ps' => 'PerfectScoreLastDay',
            'hn_p1' => 'PerfectScoreLastDayHomeName',
            'an_p1' => 'PerfectScoreLastDayAwayName',
            'cn_p1' => 'PerfectScoreLastDayComp',
            'hs_p1' => 'PerfectScoreLastDayHomeScore',
            'as_p1' => 'PerfectScoreLastDayAwayScore',
        );
        $exportConfig = array(
            'email' => 'string',
            'title' => 'string',
            'firstName' => 'string',
            'lastName' => 'string',
            'birthday' => array('date' => 'd/m/Y'),
            'country' => 'string',
            'term1' => 'string',
            'term2' => 'string',
            'date' => array('date' => 'd/m/Y'),
            'hn1' => 'string',
            'an1' => 'string',
            'hs1' => 'number',
            'as1' => 'number',
            'cn1' => 'string',
            'hn2' => 'string',
            'an2' => 'string',
            'hs2' => 'number',
            'as2' => 'number',
            'cn2' => 'string',
            'acy' => 'number',
            'pts' => 'number',
            'pl' => 'number',
            'w_ml' => 'number',
            'wt_ml' => 'string',
            'r_ml' => 'number',
            'rt_ml' => 'string',
            'ps' => 'number',
            'hn_p1' => 'string',
            'an_p1' => 'string',
            'cn_p1' => 'string',
            'hs_p1' => 'number',
            'as_p1' => 'number',
        );
        return $toCSV ? ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($maillistExportContent, $exportConfig, $aliasConfig) :
            array($maillistExportContent, $exportConfig, $aliasConfig);
    }

    public function getCombinedExportContent() {
        list($maillistExportContent, $maillistExportConfig, $maillistAliasConfig) = $this->getMaillistExportContent(false);
        $users = CustomUserDAO::getInstance($this->getServiceLocator())->getExportUsersData();

        $usersArr = array();
        foreach ($users as $user)
            $usersArr[$user['email']] = $user;

        $combinedContent = array();

        foreach ($maillistExportContent as $maillistExportRow) {
            $row = $maillistExportRow;
            if (array_key_exists($maillistExportRow['email'], $usersArr))
                $row = array_merge($row, $usersArr[$maillistExportRow['email']]);
            $combinedContent [] = $row;
        }

        $usersExportConfig = array(
            'email' => 'string',
            'title' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'birthday' => array('date' => 'd/m/Y'),
            'country' => 'string',
            'term1' => 'string',
            'term2' => 'string'
        );

        $usersAliasConfig = array(
            'title' => 'Title',
            'first_name' => 'FirstName',
            'last_name' => 'LastName',
        );

        $exportConfig = array_merge($usersExportConfig, $maillistExportConfig);
        $aliasConfig = array_merge($usersAliasConfig, $maillistAliasConfig);

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($combinedContent, $exportConfig, $aliasConfig);
    }

}