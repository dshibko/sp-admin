<?php

namespace Application\Helper;

use Application\Controller\ResultsController;
use Application\Manager\ApplicationManager;
use \Application\Manager\ContentManager;
use \Application\Manager\LanguageManager;
use Application\Manager\MessageManager;
use Application\Manager\SessionManager;
use Application\Model\DAOs\MatchDAO;
use Application\Model\DAOs\PredictionDAO;
use Application\Model\Entities\Message;
use Zend\View\Helper\AbstractHelper;

class UserMessagesHelper extends AbstractHelper
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    private $showMessages = false;

    public function __invoke()
    {
        $sessionManager = SessionManager::getInstance($this->serviceLocator);
        $applicationManager = ApplicationManager::getInstance($this->serviceLocator);
        $user = $applicationManager->getCurrentUser();
        if ($user != null && $sessionManager->getParameter(SessionManager::IS_JUST_LOGGED_IN, SessionManager::USER_STORAGE)) {
            $firstRoute = $sessionManager->getParameter(SessionManager::FIRST_ROUTE, SessionManager::USER_STORAGE);
            $match = $this->serviceLocator->get('router')->match($this->serviceLocator->get('Request'));
            $currentRoute = $match != null ? $match->getMatchedRouteName() : null;
            if ($currentRoute == null) {
                return $this;
            }
            
            if (empty($firstRoute)) {
                $sessionManager->setParameter(SessionManager::FIRST_ROUTE, $currentRoute, SessionManager::USER_STORAGE);
                $this->showMessages = true;
            } elseif ($firstRoute != $currentRoute) {
                $sessionManager->setParameter(SessionManager::IS_JUST_LOGGED_IN, false, SessionManager::USER_STORAGE);
                $messageManager = MessageManager::getInstance($this->serviceLocator);
                $messages = $messageManager->getUnviewedMessages($user->getId());
                if (!empty($messages)) {
                    foreach ($messages as $message) {
                        $message->setWasViewed(true);
                        $messageManager->saveMessage($message, false, false);
                    }
                    $messageManager->flush();
                    $messageManager->clearCache();
                }
            } else {
                $this->showMessages = true;
            }
        }
        return $this;
    }

    public function render() {
        if ($this->showMessages) {
            $applicationManager = ApplicationManager::getInstance($this->serviceLocator);
            $messageManager = MessageManager::getInstance($this->serviceLocator);
            $matchDAO = MatchDAO::getInstance($this->serviceLocator);
            $predictionDAO = PredictionDAO::getInstance($this->serviceLocator);
            $resultsController = new ResultsController();

            $user = $applicationManager->getCurrentUser();
            $messages = $messageManager->getUnviewedMessages($user->getId(), 'DESC');

            $html = '';

            $hideMessageUrl = $this->serviceLocator->get('router')->assemble(array(), array('name' => 'hide-message', 'force_canonical' => true));
            if (!empty($messages)) {
                foreach($messages as $key => $message) {
                    if ($message->getMessageType() == Message::MATCH_REPORT_TYPE) {
                        $match = $message->getMatchReportMessage()->getPrediction()->getMatch();
                        $matchInfo = $matchDAO->getMatchInfo($match->getId(), true);
                        $matchInfo['goals'] = $matchDAO->getMatchGoals($match->getId(), true);
                        $matchInfo['localStartTime'] = ApplicationManager::getInstance($this->serviceLocator)->getLocalTime($matchInfo['startTime'], $matchInfo['timezone']);
                        $matchInfo['prediction'] = $predictionDAO->getUserPrediction($match->getId(), $user->getId(), true);
                        $matchInfo['goals'] = $resultsController->getScorers($matchInfo['goals'], $matchInfo);

                        $predictionPlayers = $matchInfo['prediction']['predictionPlayers'];
                        $predictionPlayersCount = 0;
                        foreach ($predictionPlayers as $predictionPlayer)
                            if ($predictionPlayer['playerId'] != null)
                                $predictionPlayersCount++;
                        $matchInfo['prediction'] = array_merge($matchInfo['prediction'], $resultsController->getScorers($predictionPlayers, $matchInfo));
                        unset($matchInfo['prediction']['predictionPlayers']);

                        $matchInfo['accuracy'] = $matchInfo['prediction']['isCorrectResult']  + $matchInfo['prediction']['isCorrectScore'];
                        $divider = 2;
                        if ($predictionPlayersCount > 0) {
                            $matchInfo['accuracy'] += $matchInfo['prediction']['correctScorers'] / $predictionPlayersCount + $matchInfo['prediction']['correctScorersOrder'] / $predictionPlayersCount;
                            $divider = 4;
                        }
                        $matchInfo['accuracy'] /= $divider;
                        $matchInfo['accuracy'] = floor(100 * $matchInfo['accuracy']);

                        $fullReportUrl = $this->serviceLocator->get('router')->assemble($key > 0 ? array('back'=>$key) : array(), array('name' => 'results', 'force_canonical' => true));

                        $view = new \Zend\View\Renderer\PhpRenderer();
                        $resolver = new \Zend\View\Resolver\TemplateMapResolver();

                        $resolver->setMap(array(
                            'messagesTemplate' => __DIR__ . '/../../../../../module/Application/view/partials/match-report-messages.phtml',
                        ));
                        $view->setResolver($resolver);

                        $viewMessages = new \Zend\View\Model\ViewModel();
                        $viewMessages->setTemplate('messagesTemplate')
                            ->setVariables(array(
                                'matchInfo' => $matchInfo,
                                'translator' => $this->serviceLocator->get('translator'),
                                'hideMessageUrl' => $hideMessageUrl,
                                'fullReportUrl' => $fullReportUrl,
                                'messageId' => $message->getId(),
                            ));

                        $html .= $view->render($viewMessages);
                    }
                }
            } else {
                return '';
            }

            return $html;
        }

        return '';
    }
}