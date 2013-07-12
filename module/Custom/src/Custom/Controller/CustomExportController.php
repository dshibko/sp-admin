<?php

namespace Custom\Controller;

use Application\Manager\ExportManager;
use Application\Manager\FTPManager;
use Application\Manager\LogManager;
use Application\Manager\UserManager;
use Custom\Model\Helper\CustomMessagesConstants;
use \Zend\Log\Logger;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Zend\Console\Adapter\AdapterInterface as Console;
use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\Console\Exception\RuntimeException;

class CustomExportController extends AbstractActionController {

    public function usersAction() {

        error_reporting(E_ERROR | E_PARSE);

        $console = $this->getConsole();

        try {

            $logManager = LogManager::getInstance($this->getServiceLocator());
            $userManager = UserManager::getInstance($this->getServiceLocator());
            $ftpManager = FTPManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $logManager->logCustomMessage(CustomMessagesConstants::INFO_USERS_EXPORT_STARTED);
            $console->writeLine("");
            $console->writeLine(CustomMessagesConstants::INFO_USERS_EXPORT_STARTED);

            $aliasConfig = array(
                'title' => 'Title',
                'first_name' => 'Forename',
                'last_name' => 'Surname',
                'email' => 'Email',
                'date' => 'SPG_EntryDate',
                'birthday' => 'DateofBirth',
                'country' => 'County',
                'term1' => 'CFCEmailOptIn',
                'term2' => 'CFC3rdPartyOptIn'
            );

            $usersExportContent = $userManager->getUsersExportContentWithoutFacebookData($aliasConfig);

            $usersExportFileName = 'users_' . date('dmY') . '.csv';
            $usersExportFilePath = $exportManager->saveExportFile($usersExportFileName, $usersExportContent);
            if ($usersExportFilePath === false)
                throw new \Exception(CustomMessagesConstants::ERROR_USERS_EXPORT_FILE_SAVING_FAILED);

            $logManager->logCustomMessage(CustomMessagesConstants::INFO_USERS_EXPORT_FILE_SAVED_SUCCESSFULLY);
            $console->writeLine(CustomMessagesConstants::INFO_USERS_EXPORT_FILE_SAVED_SUCCESSFULLY);

            $remoteFilePath = $usersExportFileName;

            list($ftpHost, $ftpUser, $ftpPassword) = $this->getUsersFTPConfig();

            $uploadSuccess = $ftpManager->sendFile($usersExportFilePath, $remoteFilePath, $ftpHost, $ftpUser, $ftpPassword);

            if ($uploadSuccess === false)
                throw new \Exception(CustomMessagesConstants::ERROR_USERS_EXPORT_FILE_UPLOAD_FAILED);

            $logManager->logCustomMessage(CustomMessagesConstants::INFO_USERS_EXPORT_FILE_UPLOADED_SUCCESSFULLY);
            $console->writeLine(CustomMessagesConstants::INFO_USERS_EXPORT_FILE_UPLOADED_SUCCESSFULLY);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleCustomException($e, Logger::ERR, $console);
        }

    }

    /**
     * @return \Zend\Console\Adapter\AdapterInterface
     * @throws \Zend\Console\Exception\RuntimeException
     */
    private function getConsole() {
        $console = $this->getServiceLocator()->get('console');
        if (!$console instanceof Console)
            throw new RuntimeException(MessagesConstants::ERROR_RUN_OUT_OF_CONSOLE);
        return $console;
    }

    private function getUsersFTPConfig() {
        $config = $this->getServiceLocator()->get('config');
        if (!empty($config) && array_key_exists('users-export-ftp', $config) && is_array($config['users-export-ftp'])) {
            $ftpConfig = $config['users-export-ftp'];
            if (array_key_exists('host', $ftpConfig) && array_key_exists('user', $ftpConfig) && array_key_exists('password', $ftpConfig))
                return array($ftpConfig['host'], $ftpConfig['user'], $ftpConfig['password']);
        }
        throw new \Exception(CustomMessagesConstants::ERROR_EXPORT_FTP_WRONG_CONFIG);
    }

}
