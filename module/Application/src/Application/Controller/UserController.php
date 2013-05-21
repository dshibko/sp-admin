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
use Application\Form\SettingsLanguageForm;
use Application\Form\SettingsEmailSettingsForm;
use Application\Form\SettingsPublicProfileForm;
use Application\Manager\AuthenticationManager;
use Application\Manager\LogManager; //TODO remove this

class UserController extends AbstractActionController
{
    const FORM_TYPE_CHANGE_PASSWORD = 'change_password';
    const FORM_TYPE_CHANGE_EMAIL = 'change_email';
    const FORM_TYPE_CHANGE_DISPLAY_NAME = 'change_display_name';
    const FORM_TYPE_CHANGE_AVATAR = 'change_avatar';
    const FORM_TYPE_CHANGE_LANGUAGE = 'change_language';
    const FORM_TYPE_CHANGE_EMAIL_SETTINGS = 'change_email_settings';
    const FORM_TYPE_CHANGE_PUBLIC_PROFILE_OPTION = 'change_public_profile';

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
            //Language Form
            $languageForm = new SettingsLanguageForm(self::FORM_TYPE_CHANGE_LANGUAGE,$this->getServiceLocator());
            //Email Settings
            $emailSettingsForm = new SettingsEmailSettingsForm(self::FORM_TYPE_CHANGE_EMAIL_SETTINGS, $this->getServiceLocator());
            //Public Profile
            $publicProfileForm = new SettingsPublicProfileForm(self::FORM_TYPE_CHANGE_PUBLIC_PROFILE_OPTION, $this->getServiceLocator());
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
                        $post = array_merge_recursive(
                            $request->getPost()->toArray(),
                            $request->getFiles()->toArray()
                        );
                        $avatarForm->setData($post);
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeAvatarForm($avatarForm, $post)) {
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_NEW_AVATAR_SAVED);
                            $success = true;
                        }
                        break;
                    }

                    //Change Language
                    case self::FORM_TYPE_CHANGE_LANGUAGE : {
                        $languageForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeLanguageForm($languageForm)) {
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_NEW_LANGUAGE_SAVED);
                            $success = true;
                        }
                        break;
                    }

                    //Change Email Settings
                    case self::FORM_TYPE_CHANGE_EMAIL_SETTINGS : {
                        //TODO process email settings
                        $emailSettingsForm->setData($request->getPost());
                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_NEW_EMAIL_SETTINGS_SAVED);
                        $success = true;
                        break;
                    }
                    //Change Public Profile
                    case self::FORM_TYPE_CHANGE_PUBLIC_PROFILE_OPTION : {
                        $publicProfileForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangePublicProfileForm($publicProfileForm)) {
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_PUBLIC_PROFILE_OPTION_SAVED);
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
            'avatarForm' => $avatarForm,
            'languageForm' => $languageForm,
            'emailSettingsForm' => $emailSettingsForm,
            'publicProfileForm' => $publicProfileForm
        );
    }
    //TODO delete facebook app if facebook user
    public function deleteAction()
    {
        try {
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            if (!$user) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ACCESS_DENIED_NEED_LOGIN);
                return $this->redirect()->toRoute('login');
            }
            $request = $this->getRequest();

            if ($request->isPost()) {
                $user_id = (int)base64_decode($request->getPost('user_id'));
                if ($user_id !== $user->getId()){
                    throw new \Exception(MessagesConstants::FAILED_TO_DELETE_ACCOUNT_INCORRECT_ID);
                }
                if (UserManager::getInstance($this->getServiceLocator())->deleteAccount($user)){
                    AuthenticationManager::getInstance($this->getServiceLocator())->logout();
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_DELETE_ACCOUNT);
                    return $this->redirect()->toRoute('login');
                }
            }
            return array(
                'user' => $user
            );
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('user-settings');
        }
    }
    //Callback for deauthorise facebook app
    public function deAuthoriseFacebookAppAction()
    {
        $response = $this->getResponse();
        $response->setContent('failed');
        try {
            $facebook = $this->getServiceLocator()->get('facebook');
            $signedRequest = $facebook->getSignedRequest();
            if (empty($signedRequest['user_id'])){
               throw new \Exception(MessagesConstants::ERROR_CANNOT_GET_FACEBOOK_USER_ID_FROM_REQUEST . var_dump($signedRequest, true));
            }
            $user = UserManager::getInstance($this->getServiceLocator())->getUserByFacebookId($signedRequest['user_id']);
            if (empty($user)){
                throw new \Exception(MessagesConstants::ERROR_CANNOT_GET_USER_BY_FACEBOOK_ID . var_dump($signedRequest['user_id'], true));
            }
            UserManager::getInstance($this->getServiceLocator())->deleteAccount($user);

            $response->setContent('ok');
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        return $response;
    }
}