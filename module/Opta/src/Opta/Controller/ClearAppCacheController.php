<?php

namespace Opta\Controller;

use \Application\Manager\CacheManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Zend\Mvc\Controller\AbstractActionController;

class ClearAppCacheController extends AbstractActionController {

    const OK_MESSAGE = 'OK';
    const FAIL_MESSAGE = 'FAIL';

    public function indexAction() {

        $entities = $this->params()->fromRoute('entities', '');

        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/html');

        try {

            if (empty($entities))
                apc_clear_cache('user');
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
