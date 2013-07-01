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
    const GRAPH_API_URL = 'https://graph.facebook.com/';

    /**
     * @var FacebookManager
     */
    private static $instance;
    /**
     *  @var \Facebook
    */
    private $facebookAPI;

    /**
     * @param array $fUser
     * @return array
     */
    public function getFacebookUserData(array $fUser)
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

    /**
     * @param \BaseFacebook $facebookAPI
     * @return $this
     */
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
     * @param array $data
     * @return User
     */
    public function registerUser(array $data)
    {
        $data['avatar'] = AvatarDAO::getInstance($this->getServiceLocator())->findOneById(RegistrationManager::DEFAULT_AVATAR_ID); //set default avatar
        $data['country'] = ApplicationManager::DEFAULT_COUNTRY_ID;  //set default country
        return RegistrationManager::getInstance($this->getServiceLocator())->register($data);
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateUser(User $user, array $data)
    {
        $user->populate($data);
        UserDAO::getInstance($this->getServiceLocator())->save($user);
        return $user;
    }

    /**
     * @param $facebookAccessToken
     * @param $facebookId
     * @return array
     */
    public function getFacebookUserInfo($facebookAccessToken, $facebookId)
    {
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

    /**
     * @param User $user
     * @return array
     */
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