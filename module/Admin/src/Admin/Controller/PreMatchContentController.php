<?php

namespace Admin\Controller;

use \Admin\Form\MatchReportContentFieldset;
use \Admin\Form\MatchReportContentForm;
use \Application\Model\Entities\ShareCopy;
use \Admin\Form\PreMatchReportCopyForm;
use \Application\Manager\ShareManager;
use \Zend\View\Model\ViewModel;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class PreMatchContentController extends MatchContentController {

    const ADMIN_PRE_MATCH_CONTENT_ROUTE = 'admin-pre-match-content';

    public function indexAction() {

        $preMatchReportCopyForm = new PreMatchReportCopyForm();

        $reportContentForm = $this->getReportContentForm(true);

        try {

            $shareManager = ShareManager::getInstance($this->getServiceLocator());
            $preMatchCopy = $shareManager->getCopyByTarget(ShareCopy::PRE_MATCH_REPORT, true);
            $weightLabels = array(
                1 => 'First Prediction',
                3 => 'Every Prediction',
            );
            foreach ($preMatchCopy as $aPreMatchCopy) {
                $label = $aPreMatchCopy['engine'] . '<br/>' . $weightLabels[$aPreMatchCopy['weight']];
                $preMatchReportCopyForm->addSocialField($aPreMatchCopy, $label);
            }

            $request = $this->getRequest();
            if ($request->isPost()) {
                $preMatchReportCopyForm->setData($request->getPost());
                if ($preMatchReportCopyForm->isValid()) {
                    foreach ($preMatchReportCopyForm->getElements() as $element) {
                        $name = $element->getAttribute('name');
                        if (preg_match("/share-copy-([\\d]*)/", $name) > 0) {
                            preg_match("/share-copy-([\\d]*)/", $name, $id);
                            $id = $id[1];
                            $shareManager->saveShareCopy($id, $element->getValue(), false, false);
                        }
                    }
                    $shareManager->flushAndClearCache();
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_PRE_MATCH_SHARE_COPY_UPDATED);
                    return $this->redirect()->toRoute(self::ADMIN_PRE_MATCH_CONTENT_ROUTE);
                } else
                    foreach ($preMatchReportCopyForm->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($preMatchReportCopyForm->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'preMatchReportCopyForm' => $preMatchReportCopyForm,
            'reportContentForm' => $reportContentForm,
        );

    }

    protected function getRedirectRoute()
    {
        return self::ADMIN_PRE_MATCH_CONTENT_ROUTE;
    }

    protected function getMatchReportType()
    {
        return \Application\Model\Entities\DefaultReportContent::PRE_MATCH_TYPE;
    }

}
