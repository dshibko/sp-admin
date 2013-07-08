<?php

namespace Application\Controller;

use Application\Form\SettingsTermsForm;
use Application\Manager\ContentManager;
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
use Application\Form\SettingsPublicProfileForm;
use Application\Manager\AuthenticationManager;


class UserController extends AbstractActionController
{
    const FORM_TYPE_CHANGE_PASSWORD = 'change_password';
    const FORM_TYPE_CHANGE_EMAIL = 'change_email';
    const FORM_TYPE_CHANGE_DISPLAY_NAME = 'change_display_name';
    const FORM_TYPE_CHANGE_AVATAR = 'change_avatar';
    const FORM_TYPE_CHANGE_LANGUAGE = 'change_language';
    const FORM_TYPE_CHANGE_PUBLIC_PROFILE_OPTION = 'change_public_profile';
    const FORM_TYPE_CHANGE_TERMS = 'change_terms';
    const USER_SETTINGS_PAGE_ROUTE = 'user-settings';
    const LOGIN_PAGE_ROUTE = 'login';

    public function settingsAction()
    {
        try {
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            if (!$user) {
                $this->flashMessenger()->addErrorMessage($this->getTranslator()->translate(MessagesConstants::ACCESS_DENIED_NEED_LOGIN));
                return $this->redirect()->toRoute(self::LOGIN_PAGE_ROUTE);
            }
            //Change password
            $passwordForm = new SettingsPasswordForm(self::FORM_TYPE_CHANGE_PASSWORD, $this->getServiceLocator());
            //Change email
            $emailForm = new SettingsEmailForm(self::FORM_TYPE_CHANGE_EMAIL);
            $emailForm->setInputFilter(new SettingsEmailFilter($this->getServiceLocator()));
            //Display Name form
            $displayNameForm = new SettingsDisplayNameForm(self::FORM_TYPE_CHANGE_DISPLAY_NAME, $this->getServiceLocator());
            //Avatar form
            $avatarForm = new SettingsAvatarForm(self::FORM_TYPE_CHANGE_AVATAR);
            //Language Form
            $languageForm = new SettingsLanguageForm(self::FORM_TYPE_CHANGE_LANGUAGE,$this->getServiceLocator());
            //Public Profile
            $publicProfileForm = new SettingsPublicProfileForm(self::FORM_TYPE_CHANGE_PUBLIC_PROFILE_OPTION, $this->getServiceLocator());
            $terms = ContentManager::getInstance($this->getServiceLocator())->getSetUpFormTerms();
            $termsForm = null;
            if (!empty($terms)){
                $termsForm = new SettingsTermsForm(self::FORM_TYPE_CHANGE_TERMS,$this->getServiceLocator(), $terms);
                $termsForm->initForm($user);
            }
          //  $termsForm =
            $request = $this->getRequest();
            if ($request->isPost()) {
                $type = $request->getPost('type');
                $success = false;
                switch ($type) {
                    //Change Password
                    case self::FORM_TYPE_CHANGE_PASSWORD : {
                        $passwordForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangePasswordForm($passwordForm)) {
                            $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_NEW_PASSWORD_SAVED));
                            $success = true;
                        }else{
                             $this->formErrors($passwordForm, $this);
                        }
                        break;
                    }
                    //Change Email
                    case self::FORM_TYPE_CHANGE_EMAIL : {
                        $post = $request->getPost();
                        $post['email'] = isset($post['email']) ? strtolower($post['email']) : null;
                        $post['confirm_email'] = isset($post['confirm_email']) ? strtolower($post['confirm_email']) : null;
                        $emailForm->setData($post);
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeEmailForm($emailForm)) {
                            $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_NEW_EMAIL_SAVED));
                            $success = true;
                        }else{
                            $this->formErrors($emailForm, $this);
                        }
                        break;
                    }
                    //Change Display Name
                    case self::FORM_TYPE_CHANGE_DISPLAY_NAME : {
                        $displayNameForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeDisplayNameForm($displayNameForm)) {
                            $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_NEW_DISPLAY_NAME_SAVED));
                            $success = true;
                        }else{
                            $this->formErrors($displayNameForm, $this);
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
                        $defaultAvatarId = !empty($post['default_avatar']) ? $post['default_avatar'] : null;
                        $newAvatar = UserManager::getInstance($this->getServiceLocator())->getUserAvatar($avatarForm, $defaultAvatarId);
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeAvatarForm($newAvatar)) {
                            $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_NEW_AVATAR_SAVED));
                            $success = true;
                        }else{
                            $this->formErrors($avatarForm, $this);
                        }
                        break;
                    }

                    //Change Language
                    case self::FORM_TYPE_CHANGE_LANGUAGE : {
                        $languageForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangeLanguageForm($languageForm)) {
                            $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_NEW_LANGUAGE_SAVED));
                            $success = true;
                        }else{
                            $this->formErrors($languageForm, $this);
                        }
                        break;
                    }

                    //Change Public Profile
                    case self::FORM_TYPE_CHANGE_PUBLIC_PROFILE_OPTION : {
                        $publicProfileForm->setData($request->getPost());
                        if (UserManager::getInstance($this->getServiceLocator())->processChangePublicProfileForm($publicProfileForm)) {
                            $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_PUBLIC_PROFILE_OPTION_SAVED));
                            $success = true;
                        }else{
                            $this->formErrors($publicProfileForm, $this);
                        }
                        break;
                    }
                    case self::FORM_TYPE_CHANGE_TERMS:{
                        $termsForm->setData($request->getPost());
                        if ($termsForm->isValid()){
                            $data = $termsForm->getData();
                            if (!empty($data['terms'])){
                                if (array_key_exists('term1', $data['terms'])){
                                    $user->setTerm1((int)$data['terms']['term1']);
                                }
                                if (array_key_exists('term2', $data['terms'])){
                                    $user->setTerm2((int)$data['terms']['term2']);
                                }
                            }
                            UserManager::getInstance($this->getServiceLocator())->save($user);
                            $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_TERMS_SETTINGS_SAVED));
                            $success = true;
                        }else{
                            $this->formErrors($termsForm, $this);
                        }
                        break;
                    }
                    default : {
                        throw new \Exception($this->getTranslator()->translate(MessagesConstants::ERROR_INVALID_SETTING_FORM_TYPE));
                    }
                }
                if ($success) {
                    return $this->redirect()->toRoute(self::USER_SETTINGS_PAGE_ROUTE);
                }
            }

            return array(
                'user' => $user,
                'passwordForm' => $passwordForm,
                'emailForm' => $emailForm,
                'displayNameForm' => $displayNameForm,
                'avatarForm' => $avatarForm,
                'languageForm' => $languageForm,
                'publicProfileForm' => $publicProfileForm,
                'termsForm' => $termsForm
            );

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function deleteAction()
    {
        try {
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            if (!$user) {
                $this->flashMessenger()->addErrorMessage($this->getTranslator()->translate(MessagesConstants::ACCESS_DENIED_NEED_LOGIN));
                return $this->redirect()->toRoute(self::LOGIN_PAGE_ROUTE);
            }
            $request = $this->getRequest();
            if ($request->isPost()) {
                $user_id = (int)base64_decode($request->getPost('user_id'));
                if ($user_id !== $user->getId()){
                    throw new \Exception($this->getTranslator()->translate(MessagesConstants::FAILED_TO_DELETE_ACCOUNT_INCORRECT_ID));
                }
                if (UserManager::getInstance($this->getServiceLocator())->deleteAccount($user)){
                    AuthenticationManager::getInstance($this->getServiceLocator())->logout();
                    $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_DELETE_ACCOUNT));
                    return $this->redirect()->toRoute(self::LOGIN_PAGE_ROUTE);
                }
            }
            return array(
                'user' => $user
            );
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function deAuthoriseFacebookAppAction()
    {
        $response = $this->getResponse();
        $response->setContent('failed');
        try {
            $facebook = $this->getServiceLocator()->get('facebook');
            $signedRequest = $facebook->getSignedRequest();
            if (empty($signedRequest['user_id'])){
               throw new \Exception($this->getTranslator()->translate(MessagesConstants::ERROR_CANNOT_GET_FACEBOOK_USER_ID_FROM_REQUEST) . var_dump($signedRequest, true));
            }
            $user = UserManager::getInstance($this->getServiceLocator())->getUserByFacebookId($signedRequest['user_id']);
            if (empty($user)){
                throw new \Exception($this->getTranslator()->translate(MessagesConstants::ERROR_CANNOT_GET_USER_BY_FACEBOOK_ID) . var_dump($signedRequest['user_id'], true));
            }
            UserManager::getInstance($this->getServiceLocator())->deleteAccount($user, false);

            $response->setContent('ok');
            return $response;
        } catch (\FacebookApiException $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }
    }
}