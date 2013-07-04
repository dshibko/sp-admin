<?php

namespace Admin\Controller;

use \Application\Model\Entities\Feed;
use \Opta\Manager\OptaManager;
use \Application\Model\Helpers\MessagesConstants;
use \Admin\Form\OptaForm;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OptaController extends AbstractActionController
{

    const OPTA_INDEX_ROUTE = 'admin-opta';

    public function indexAction() {
        return array(
            'form' => new OptaForm(),
            'feedTypes' => OptaManager::getInstance($this->getServiceLocator())->getAvailableFeedTypes(),
        );
    }

    public function dispatchAction() {

        error_reporting(E_ERROR | E_PARSE);

        try {

            $type = $this->params()->fromRoute('type', '');
            if (empty($type))
                throw new \Exception(MessagesConstants::ERROR_TYPE_NOT_SPECIFIED);

            if (!in_array($type, Feed::getAvailableTypes()))
                throw new \Exception(sprintf(MessagesConstants::ERROR_WRONG_TYPE_SPECIFIED, $type));

            OptaManager::getInstance($this->getServiceLocator())->dispatchFeedsByType($type, true);

            $this->flashMessenger()->addMessage(sprintf(MessagesConstants::FEEDS_DISPATCH_COMPLETED, $type));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toRoute(self::OPTA_INDEX_ROUTE);
    }

    public function uploadAction()
    {

        $form = new OptaForm();

        try {

            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()) {
                    try {
                        $feedFileArr = $form->get('feed')->getValue();
                        $feedFileName = $feedFileArr['name'];
                        $optaManager = OptaManager::getInstance($this->getServiceLocator());
                        $feedType = $optaManager->getFeedTypeByName($feedFileName);
                        if ($feedType === false)
                            throw new \Exception(MessagesConstants::ERROR_UNKNOWN_FEED_TYPE);
                        $feedFilePath = $feedFileArr['tmp_name'];
                        if (method_exists($optaManager, 'parse' . $feedType . 'Feed')) {
                            $parsingResults = $optaManager->{'parse' . $feedType . 'Feed'}($feedFilePath);
                            if ($parsingResults === true)
                                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_OPTA_FEED_PARSED);
                        }

                        @unlink($feedFilePath);

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }
        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toRoute(self::OPTA_INDEX_ROUTE);

    }

}