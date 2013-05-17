<?php

namespace Application\Manager;

use \Neoco\Manager\BasicManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Application\Model\DAOs\UserDAO;
use Application\Model\Entities\User;
use Application\Form\RegistrationForm;
use Application\Manager\RegistrationManager;
use Application\Manager\ImageManager;

class FacebookManager extends BasicManager
{
    const MALE = 'male';
    const FEMALE = 'female';
    const DEFAULT_COUNTRY = 95;
    const FACEBOOK_AVATAR_FOLDER = 'small';
    const DEFAULT_AVATAR_ID = 1;

    /**
     * @var ApplicationManager
     */
    private static $instance;
    /**
     *  @var \Facebook
    */
    private $facebookAPI;
    /**
     *  Get Profile Image from Facebook
     *
     *  @param bigint $facebook_id
     *  @return string
    */
    private function getFacebookProfileImage($facebook_id)
    {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, 'http://graph.facebook.com/'.$facebook_id.'/picture?width=72&height=72');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $fileName = ImageManager::IMAGES_DIR_PATH . ImageManager::IMAGE_TYPE_AVATAR . ImageManager::WEB_SEPARATOR . self::FACEBOOK_AVATAR_FOLDER . ImageManager::WEB_SEPARATOR . uniqid() .'.jpg' ;
        $file = fopen(ImageManager::getInstance($this->getServiceLocator())->getAppPublicPath() . $fileName, 'w+');
        fputs($file, $data);
        fclose($file);
        return $fileName;
    }

    private function getFacebookUserData(array $fUser)
    {
        $title = (
                    (strtolower($fUser['gender']) == self::MALE)
                        ? RegistrationForm::MR
                        : (
                            (strtolower($fUser['gender']) == self::FEMALE)
                                ? RegistrationForm::MS
                                : 'none'
                        )
        );

        $data = array(
            'facebook_id' => $fUser['id'],
            'email' => $fUser['email'],
            'first_name' => $fUser['first_name'],
            'last_name' => $fUser['last_name'],
            'display_name' => $fUser['name'],
            'date_of_birth' => $fUser['birthday'],
            'gender' =>  strtolower($fUser['gender']),
            'title' => $title

        );
        return $data;
    }
    /**
     *  @return \Facebook
    */
    public function getFacebookAPI()
    {
        return $this->facebookAPI;
    }

    public function setFacebookAPI(\BaseFacebook $facebookAPI)
    {
        $this->facebookAPI = $facebookAPI;
        return $this;
    }
    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ApplicationManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new FacebookManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }
    /**
     *  Register new user
     *
     * @param array $fUser
     * @return Application\Model\Entities\User
     */
    public function process(array $fUser)
    {

        $facebook_id = $fUser['id'];
        $user = UserDAO::getInstance($this->getServiceLocator())->getUserByFacebookId($facebook_id);
        $data = $this->getFacebookUserData($fUser);
        //TODO handle change password, de-authorize app, logs out facebook when getting data with access token
        //set long live access token 60 days
        $this->getFacebookAPI()->setExtendedAccessToken();
        $data['facebook_access_token'] = $this->getFacebookAPI()->getAccessToken();
        if (!empty($user)){ //Update existing member
            $user->populate($data);
            UserDAO::getInstance($this->getServiceLocator())->save($user);
            return $user;
        }else{ // New member
            $avatar = $this->getFacebookProfileImage($facebook_id);
            $data['password'] = uniqid();

            if (!empty($avatar)){
                $data['avatar'] = $avatar;
            }else{
                $data['default_avatar_id'] = self::DEFAULT_AVATAR_ID;
            }
            $data['country'] = self::DEFAULT_COUNTRY;

            return RegistrationManager::getInstance($this->getServiceLocator())->register($data);
        }


    }

}