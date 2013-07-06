<?php

namespace Application\Controller;

use Application\Manager\ApplicationManager;
use \Zend\Http\PhpEnvironment\RemoteAddress;
use \Application\Manager\CacheManager;
use \Application\Manager\ExceptionManager;
use \Zend\Mvc\Controller\AbstractActionController;

class ClearAppCacheController extends AbstractActionController {

    const OK_MESSAGE = 'OK';
    const FAIL_MESSAGE = 'FAIL';

    public function indexAction() {

        $remoteAddresses = new RemoteAddress();
        if (!in_array($remoteAddresses->getIpAddress(), ApplicationManager::getInstance($this->getServiceLocator())->getClearAppCacheAllowedIps()))
            return $this->notFoundAction();

        $entities = $this->params()->fromRoute('entities', '');

        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/html');

        try {

            if (empty($entities))
                CacheManager::getInstance($this->getServiceLocator())->clearCache();
            else {
                $entitiesArr = explode(",", $entities);
                foreach ($entitiesArr as $entity) {
                    if (class_exists($entity))
                        CacheManager::getInstance($this->getServiceLocator())->clearEntityCache($entity);
                }
            }

            $response->setContent(self::OK_MESSAGE);

        } catch (\Exception $e) {
            $response->setContent(self::FAIL_MESSAGE);
        }

        return $response;
    }

}
