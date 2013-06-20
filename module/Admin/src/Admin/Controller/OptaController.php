<?php

namespace Admin\Controller;

use \Opta\Manager\OptaManager;
use \Application\Model\Helpers\MessagesConstants;
use \Admin\Form\OptaForm;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OptaController extends AbstractActionController
{

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
                        $feedType = $form->get('type')->getValue();
                        $feedFileArr = $form->get('feed')->getValue();
                        $feedFilePath = $feedFileArr['tmp_name'];
                        $optaManager = OptaManager::getInstance($this->getServiceLocator());
                        if (method_exists($optaManager, 'parse' . $feedType . 'Feed')) {
                            $parsingResults = $optaManager->{'parse' . $feedType . 'Feed'}($feedFilePath);
                            if ($parsingResults === true)
                                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_OPTA_FEED_PARSED);
//                            else
//                                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_OPTA_FEED_PARSER_CRASHED);
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

        return array(
            'form' => $form,
        );

    }

}