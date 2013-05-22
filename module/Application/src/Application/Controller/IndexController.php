<?php

namespace Application\Controller;

use \Application\Manager\ContentManager;
use \Application\Manager\RegionManager;
use \Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {
    
    public function indexAction() {

        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $region = $regionManager->getDefaultRegion(); // TODO to replace by GeoIP
        $content = $region->getRegionContent();
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        $gameplayBlocks = $contentManager->getGameplayBlocks($region, true);

        $viewModel = new ViewModel(array(
            'content' => $content,
            'blocks' => $gameplayBlocks,
        ));

        $viewModel->setTerminal(true);
        return $viewModel;

    }

}
