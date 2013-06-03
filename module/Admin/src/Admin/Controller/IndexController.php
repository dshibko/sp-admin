<?php

namespace Admin\Controller;

use \Application\Manager\MatchManager;
use \Application\Manager\PredictionManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Application\Manager\UserManager;
use \Application\Manager\ApplicationManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \Admin\Form\AccountForm;
use \Application\Manager\AuthenticationManager;
use \Admin\Form\AccountPasswordForm;

class IndexController extends AbstractActionController
{

    const ADMIN_LOGIN_PAGE_ROUTE = 'admin-login';
    const ADMIN_ACCOUNT_PAGE = 'admin-account';
    const PERSONAL_INFO_FORM_TYPE = 'personal_info';
    const PASSWORD_FORM_TYPE = 'password_form';

    public function indexAction()
    {

        $config = $this->getServiceLocator()->get('config');
        $rules = $config['zfcrbac']['firewalls']['ZfcRbac\Firewall\Route'];
        $rbac = $this->getServiceLocator()->get('ZfcRbac\Service\Rbac');
        $router = new \ZfcRbac\Firewall\Route($rules);
        $router->setRbac($rbac);
        if (!$router->isGranted('admin'))
            return $this->redirect()->toRoute(self::ADMIN_LOGIN_PAGE_ROUTE);

        $nextMatchId = $prevMatchId = -1;

        try {
            $userManager = UserManager::getInstance($this->getServiceLocator());
            $registeredUsersNumber = $userManager->getRegisteredUsersNumber();
            $usersRegisteredInPast7Days = $userManager->getUsersRegisteredInPastDays(7, true);
            $currentSeason = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
            if ($currentSeason == null) {
                $activeUsersNumber = $inactiveUsersNumber = $avgNumberOfPredictions =
                $nextMatchPredictions = $prevMatchPredictions = MessagesConstants::INFO_ADMIN_OUT_OF_SEASON;
            } else {
                $activeUsersNumber = $userManager->getActiveUsersNumber($currentSeason);
                $inactiveUsersNumber = $registeredUsersNumber - $activeUsersNumber;

                $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
                $avgNumberOfPredictions = $predictionManager->getAvgNumberOfPredictions($currentSeason);
                $matchManager = MatchManager::getInstance($this->getServiceLocator());
                $nextMatch = $matchManager->getNextMatch();
                if ($nextMatch != null) {
                    $nextMatchPredictions = $nextMatch->getPredictions()->count();
                    $nextMatchId = $nextMatch->getId();
                } else
                    $nextMatchPredictions = MessagesConstants::INFO_ADMIN_NO_NEXT_MATCH;
                $prevMatch = $matchManager->getPrevMatch();
                if ($prevMatch != null) {
                    $prevMatchPredictions = $prevMatch->getPredictions()->count();
                    $prevMatchId = $prevMatch->getId();
                } else
                    $prevMatchPredictions = MessagesConstants::INFO_ADMIN_NO_PREV_MATCH;
            }

        } catch (\Exception $e) {
            $registeredUsersNumber = $activeUsersNumber = $inactiveUsersNumber =
            $avgNumberOfPredictions = $nextMatchPredictions = $prevMatchPredictions = 'N\A';
            $usersRegisteredInPast7Days = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
            'registeredUsersNumber' => $registeredUsersNumber,
            'activeUsersNumber' => $activeUsersNumber,
            'inactiveUsersNumber' => $inactiveUsersNumber,
            'avgNumberOfPredictions' => $avgNumberOfPredictions,
            'nextMatchPredictions' => $nextMatchPredictions,
            'prevMatchPredictions' => $prevMatchPredictions,
            'usersRegisteredInPast7Days' => $usersRegisteredInPast7Days,
            'nextMatchId' => $nextMatchId,
            'prevMatchId' => $prevMatchId,
        ));

    }

    public function myAccountAction()
    {
        $personalInfoForm = new AccountForm(self::PERSONAL_INFO_FORM_TYPE);
        $passwordForm = new AccountPasswordForm(self::PASSWORD_FORM_TYPE);

        try {
            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
            $userManager = UserManager::getInstance($this->getServiceLocator());
            $user = $applicationManager->getCurrentUser();
            if (empty($user)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_USER);
                return $this->redirect()->toRoute(self::ADMIN_LOGIN_PAGE_ROUTE);
            }
            $request = $this->getRequest();
            if ($request->isPost()) {
                $type = $request->getPost('type');
                switch($type){

                    //Personal Data
                    case self::PERSONAL_INFO_FORM_TYPE : {
                        $personalInfoForm->setData($request->getPost());
                        $personalInfoForm->setInputFilter($this->getServiceLocator()->get('accountFilter'));
                        $email = $personalInfoForm->get('email')->getValue();
                        $oldEmail = false;
                        //Disable checking email if it is old email
                        if ($email === $user->getEmail()) {
                            // create new validator chain
                            $newValidatorChain = new \Zend\Validator\ValidatorChain;
                            // loop through all validators of the validator chained currently attached to the element
                            foreach ($personalInfoForm->getInputFilter()->get('email')->getValidatorChain()->getValidators() as $validator) {
                                // attach validator unless it's instance of Zend\Validator\EmailAddress
                                if (!($validator['instance'] instanceof \DoctrineModule\Validator\NoObjectExists)) {
                                    $newValidatorChain->attach($validator['instance'], $validator['breakChainOnFailure']);
                                }
                            }
                            // replace the old validator chain on the element
                            $personalInfoForm->getInputFilter()->get('email')->setValidatorChain($newValidatorChain);
                        }else{
                            $oldEmail = $user->getEmail();
                        }

                        if ($personalInfoForm->isValid()) {
                            $data = $personalInfoForm->getData();
                            $user->setFirstName($data['firstName'])
                                ->setLastName($data['lastName'])
                                ->setEmail($data['email']);
                            $userManager->save($user);
                            //if email was changed - update identity
                            if ($oldEmail){
                                AuthenticationManager::getInstance($this->getServiceLocator())->changeIdentity($oldEmail, $user->getEmail());
                            }
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_ACCOUNT_SAVED);
                            return $this->redirect()->toRoute(self::ADMIN_ACCOUNT_PAGE);
                        } else {
                            foreach ($personalInfoForm->getMessages() as $el => $messages) {
                                $this->flashMessenger()->addErrorMessage($personalInfoForm->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages));
                            }
                        }
                        break;
                    }
                    //Password form
                    case self::PASSWORD_FORM_TYPE :{
                        $passwordForm->setData($request->getPost());
                        if ($passwordForm->isValid()){
                            $data = $passwordForm->getData();
                            //Check Old Password
                            if ($user->getPassword() !== md5($data['password'])){
                                throw new \Exception(MessagesConstants::ERROR_INVALID_OLD_PASSWORD);
                            }
                            $user->setPassword(md5($data['new_password']));
                            $userManager->save($user);
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_NEW_PASSWORD_SAVED);
                            return $this->redirect()->toRoute(self::ADMIN_ACCOUNT_PAGE);
                        }else{
                            foreach ($passwordForm->getMessages() as $el => $messages) {
                                $this->flashMessenger()->addErrorMessage($passwordForm->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages));
                            }
                        }
                        break;
                    }

                    default:{
                        throw new \Exception(MessagesConstants::ERROR_INVALID_SETTING_FORM_TYPE);
                    }
                }

            }
            $personalInfoForm->populateValues($user->getArrayCopy());

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        $passwordForm->populateValues(array(
            'password' => '',
            'new_password' => '',
            'confirm_new_password' => ''
        ));
        return array(
            'personalInfoForm' => $personalInfoForm,
            'passwordForm' => $passwordForm,
            'title' => 'My Account'
        );
    }

}
