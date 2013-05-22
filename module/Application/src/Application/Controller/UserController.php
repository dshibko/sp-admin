<?php

namespace Application\Controller;

use \Neoco\Controller\AbstractActionController;
use \Application\Manager\ExceptionManager;
use \Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use Application\Form\SettingsPasswordForm;
use Application\Manager\UserManager;
use Application\Form\SettingsEmailForm;
use Application\Form\Filter\SettingsEmailFilter;
use Application\Form\SettingsDisplayNameForm;
use Application\Form\SettingsAvatarForm;

class UserController extends AbstractActionController
{
    const FORM_TYPE_CHANGE_PASSWORD = 'change_password';
    const FORM_TYPE_CHANGE_EMAIL = 'change_email';
    const FORM_TYPE_CHANGE_DISPLAY_NAME = 'change_display_name';
    const FORM_TYPE_CHANGE_AVATAR = 'change_avatar';
    const FORM_TYPE_CHANGE_LANGUAGE = 'change_language';
    const FORM_TYPE_CHANGE_EMAIL_SETTINGS = 'change_email_settings';

    public function settingsAction()
    {
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        try {
            if (!$user) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ACCESS_DENIED_NEED_LOGIN);
                return $this->redirect()->toRoute('login');
            }
            //Change password
            $passwordForm = new SettingsPasswordForm(self::FORM_TYPE_CHANGE_PASSWORD);
            //Change email
            $emailForm = new SettingsEmailForm(self::FORM_TYPE_CHANGE_EMAIL);
            $emailForm->setInputFilter(new SettingsEmailFilter($this->getServiceLocator()));
            //Display Name form
            $displayNameForm = new SettingsDisplayNameForm(self::FORM_TYPE_CHANGE_DISPLAY_NAME, $this->getServiceLocator());
            //Avatar form
            $avatarForm = new SettingsAvatarForm(self::FORM_TYPE_CHANGE_AVATAR);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $type = $request->getPost('type');
                $success = false;
                switch ($type) {
                    //Change Password
                    case self::FORM_TYPE_CHANGE_PASSWORD : {
                        $passwordForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangePasswordForm($passwordForm)) {
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_NEW_PASSWORD_SAVED);
                            $success = true;
                        }
                        break;
                    }
                    //Change Email
                    case self::FORM_TYPE_CHANGE_EMAIL : {
                        $emailForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeEmailForm($emailForm)) {
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_NEW_EMAIL_SAVED);
                            $success = true;
                        }
                        break;
                    }
                    //Change Display Name
                    case self::FORM_TYPE_CHANGE_DISPLAY_NAME : {
                        $displayNameForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeDisplayNameForm($displayNameForm)) {
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_NEW_DISPLAY_NAME_SAVED);
                            $success = true;
                        }
                        break;
                    }

                    //Change Avatar
                    case self::FORM_TYPE_CHANGE_AVATAR : {
                        $avatarForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeAvatarForm($avatarForm)) {
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_NEW_AVATAR_SAVED);
                            $success = true;
                        }
                        break;
                    }
                    default : {
                        throw new \Exception(MessagesConstants::ERROR_INVALID_SETTING_FORM_TYPE);
                    }
                }

                if ($success) {
                    return $this->redirect()->toRoute('user-settings');
                }
            }

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('user-settings');
        }

        return array(
            'user' => $user,
            'passwordForm' => $passwordForm,
            'emailForm' => $emailForm,
            'displayNameForm' => $displayNameForm,
            'avatarForm' => $avatarForm
        );
    }
}