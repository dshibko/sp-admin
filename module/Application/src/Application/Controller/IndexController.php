<?php

namespace Application\Controller;

use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Manager\ApplicationManager;
use \Application\Manager\ContentManager;
use \Application\Manager\LanguageManager;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    const SETUP_PAGE_ROUTE = 'setup';
    const PREDICT_PAGE_ROUTE = 'predict';

    public function indexAction() {


        try {

            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            if ($user != null)
                if (!$user->getIsActive())
                    return $this->redirect()->toRoute(self::SETUP_PAGE_ROUTE);
                else
                    return $this->redirect()->toRoute(self::PREDICT_PAGE_ROUTE);

            $contentManager = ContentManager::getInstance($this->getServiceLocator());
            $languageManager = LanguageManager::getInstance($this->getServiceLocator());
            $language = $languageManager->getSelectedLanguage();
            $defaultLanguage = $languageManager->getDefaultLanguage();

            $content = $contentManager->getLanguageContent($language, true);
            $defaultContent = $contentManager->getLanguageContent($defaultLanguage, true);
            $gameplayBlocks = $contentManager->getGameplayBlocks($language, true);
            $defaultGameplayBlocks = $contentManager->getGameplayBlocks($defaultLanguage, true);

            $content = $contentManager->extendContent($defaultContent, $content);
            $gameplayBlocks = $contentManager->extendContent($defaultGameplayBlocks, $gameplayBlocks);

            $viewModel = new ViewModel(array(
                'content' => $content,
                'blocks' => $gameplayBlocks,
            ));

            $viewModel->setTerminal(true);
            return $viewModel;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

}
