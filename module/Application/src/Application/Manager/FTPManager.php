<?php

namespace Application\Manager;

use Application\Model\Helpers\MessagesConstants;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class FTPManager extends BasicManager {

    /**
     * @var FTPManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return FTPManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new FTPManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param string $ftpHost
     * @param string $ftpUser
     * @param string $ftpPass
     * @param bool $passive
     * @throws \Exception
     * @return resource
     */
    private function connect($ftpHost, $ftpUser, $ftpPass, $passive = false) {

        $ftpConnection = @ftp_connect($ftpHost);
        if ($ftpConnection === false)
            throw new \Exception(sprintf(MessagesConstants::ERROR_FTP_CANNOT_CONNECT_TO_THE_HOST, $ftpHost));

        try {
            $loginSuccess = @ftp_login( $ftpConnection, $ftpUser, $ftpPass);
        } catch (\Exception $e) {
            $loginSuccess = false;
        }

        if ($loginSuccess === false)
            throw new \Exception(sprintf(MessagesConstants::ERROR_FTP_CANNOT_LOGIN_TO_THE_HOST, $ftpHost, $ftpUser));

        if (@ftp_pasv($ftpConnection, $passive) === false)
            throw new \Exception(sprintf(MessagesConstants::ERROR_FTP_CANNOT_CONFIGURE_PASSIVE_MODE, $ftpHost));

        return $ftpConnection;

    }

    private function disconnect($ftpConnection) {

        ftp_close($ftpConnection);

    }

    public function sendFile($localFilePath, $remoteFilePath, $ftpHost, $ftpUser, $ftpPass, $mode = FTP_ASCII) {

        $ftpConnection = $this->connect($ftpHost, $ftpUser, $ftpPass, true);
        $uploadSuccess = @ftp_put($ftpConnection, $remoteFilePath, $localFilePath, $mode);
        $this->disconnect($ftpConnection);

        return $uploadSuccess;

    }

}
