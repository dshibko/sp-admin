<?php

namespace Application\Controller;

use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Manager\ApplicationManager;
use \Application\Manager\ContentManager;
use \Application\Manager\RegionManager;
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
            $regionManager = RegionManager::getInstance($this->getServiceLocator());
            $region = $regionManager->getSelectedRegion();

            $content = $contentManager->getRegionContent($region, true);
            $gameplayBlocks = $contentManager->getGameplayBlocks($region, true);

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
