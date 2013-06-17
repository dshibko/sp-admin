<?php

namespace Application\Manager;

use \Neoco\Manager\BasicManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Application\Model\DAOs\UserDAO;
use \Application\Model\DAOs\AvatarDAO;
use Application\Model\Entities\User;
use Application\Form\RegistrationForm;
use Application\Manager\RegistrationManager;
use Application\Manager\ImageManager;
use Application\Helper\AvatarHelper;
use Application\Model\Helpers\MessagesConstants;


class FacebookManager extends BasicManager
{
    const MALE = 'male';
    const FEMALE = 'female';
    const FACEBOOK_AVATAR_FOLDER = 'small';
    const DEFAULT_AVATAR_ID = 1;
    const GRAPH_API_URL = 'https://graph.facebook.com/';

    /**
     * @var FacebookManager
     */
    private static $instance;
    /**
     *  @var \Facebook
    */
    private $facebookAPI;

    //TODO don't save facebook image
    /**
     *  Get Profile Image from Facebook
     *
     *  @param bigint $facebook_id
     *  @return string
    */
    /*private function getFacebookProfileImage($facebook_id)
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
    }*/

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
        if (null == $this->facebookAPI){
            $this->facebookAPI = $this->getServiceLocator()->get('facebook');
        }
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
     * @return FacebookManager
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
     * @return \Application\Model\Entities\User
     */
    public function process(array $fUser)
    {
        $facebook_id = $fUser['id'];
        //Check if user wants to connect to a Facebook account
        $currentUser = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();

        $user = !empty($currentUser) ? $currentUser : UserDAO::getInstance($this->getServiceLocator())->getUserByFacebookId($facebook_id);
        $data = $this->getFacebookUserData($fUser);
        //set long live access token 60 days
        $this->getFacebookAPI()->setExtendedAccessToken();
        $data['facebook_access_token'] = $this->getFacebookAPI()->getAccessToken();
        if (!empty($user)){ //Update existing member
            $old_email = $user->getEmail();
            $user->populate($data);
            UserDAO::getInstance($this->getServiceLocator())->save($user);
            if (!empty($currentUser)){
                AuthenticationManager::getInstance($this->getServiceLocator())->changeIdentity($old_email, $user->getEmail());
            }
            return $user;
        }else{ // New member
            $data['password'] = uniqid(); //Set default password
            $data['avatar'] = AvatarDAO::getInstance($this->getServiceLocator())->findOneById(self::DEFAULT_AVATAR_ID); //set default avatar
            $data['country'] = ApplicationManager::DEFAULT_COUNTRY_ID;  //set default country
            // $avatar = $this->getFacebookProfileImage($facebook_id);
            /*if (!empty($avatar)){
                $avatarHelper = new AvatarHelper();
                $data['avatar'] =  $avatarHelper->setPath($avatar)->setNewAvatar()->getAvatar();
            }else{
                $data['avatar'] = AvatarDAO::getInstance($this->getServiceLocator())->findOneById(self::DEFAULT_AVATAR_ID);
            }*/


            return RegistrationManager::getInstance($this->getServiceLocator())->register($data);
        }
    }

    public function getFacebookUserInfo($facebookAccessToken, $facebookId)
    { //TODO handle change password, de-authorize app, logs out facebook when getting data with access token
        $data = array();
        try {
            $this->getFacebookAPI()->setAccessToken($facebookAccessToken);
            $info = $this->getFacebookAPI()->api('/' . $facebookId);
            //Get user friends count
            $userFriendsCountQuery = 'SELECT friend_count FROM user WHERE uid = ' . $facebookId;
            $userFriendsCount = $this->getFacebookAPI()->api(array(
                'method' => 'fql.query',
                'query' => $userFriendsCountQuery
            ));
            // Get link to avatar
            //TODO create method to get url by user
            $avatarLink = self::GRAPH_API_URL . $facebookId .'/picture?type=large';
            //Get user likes
            $userLikesQuery = 'SELECT name FROM page WHERE page_id IN (SELECT page_id FROM page_fan WHERE uid = '.$facebookId.')';

            $userLikesArr = $this->getFacebookAPI()->api(array(
                'method' => 'fql.query',
                'query' => $userLikesQuery
            ));
            $userLikes = array();
            foreach ($userLikesArr as $userLike)
                $userLikes []= $userLike['name'];

            $checkInsArr = $this->getFacebookAPI()->api('/'.$facebookId.'/checkins');
            $checkIns = array();
            foreach ($checkInsArr['data'] as $checkIn)
                $checkIns []= $checkIn['id'];

            $keysPrefix = 'facebook_';
            $data = array(
                $keysPrefix . 'id' => isset($info['id']) ? $info['id'] : null,
                $keysPrefix . 'first_name' => isset($info['first_name']) ? $info['first_name'] : null,
                $keysPrefix . 'last_name' =>  isset($info['last_name']) ? $info['last_name'] : null,
                $keysPrefix . 'username' => isset($info['username']) ? $info['username'] : null,
                $keysPrefix . 'email' =>  isset($info['email']) ? $info['email'] : null,
                $keysPrefix . 'avatar_link' => $avatarLink,
                $keysPrefix . 'gender' => isset($info['gender']) ? $info['gender'] : null,
                $keysPrefix . 'date_of_birth' => isset($info['birthday']) ? $info['birthday'] : null,
                $keysPrefix . 'locale' => isset($info['locale']) ? $info['locale'] : null,
                $keysPrefix . 'number_of_friends' => !empty($userFriendsCount[0]['friend_count']) ? $userFriendsCount[0]['friend_count'] : 0,
                $keysPrefix . 'user_likes' =>  $userLikes,
                $keysPrefix . 'user_checkins' => $checkIns,
            );
        } catch (\Exception $e) {}
        return $data;
    }

    public function getFriendsUsers(User $user) {
        $this->getFacebookAPI()->setAccessToken($user->getFacebookAccessToken());
        $userFriendsQuery = 'SELECT uid2 FROM friend WHERE uid1 = ' . $user->getFacebookId();
        $facebookFriends = $this->getFacebookAPI()->api(array(
            'method' => 'fql.query',
            'query' => $userFriendsQuery,
        ));
        $uids = array();
        foreach($facebookFriends as $facebookFriend)
            $uids [] = $facebookFriend["uid2"];
        return $uids;
    }
}