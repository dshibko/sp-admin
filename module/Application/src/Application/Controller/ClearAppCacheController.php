<?php

namespace Application\Controller;

use \Zend\Http\PhpEnvironment\RemoteAddress;
use \Application\Manager\CacheManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Zend\Mvc\Controller\AbstractActionController;

class ClearAppCacheController extends AbstractActionController {

    const OK_MESSAGE = 'OK';
    const FAIL_MESSAGE = 'FAIL';

    public function indexAction() {

        $remoteAddresses = new RemoteAddress();
        var_dump($remoteAddresses->getIpAddress());
        die;
        if ($remoteAddresses->getIpAddress() !== '127.0.0.1')
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
