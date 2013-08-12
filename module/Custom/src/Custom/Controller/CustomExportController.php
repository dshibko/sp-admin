<?php

namespace Custom\Controller;

use Application\Manager\ExportManager;
use Application\Manager\FTPManager;
use Application\Manager\LogManager;
use Application\Manager\UserManager;
use Custom\Manager\CustomExportManager;
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

            list($ftpHost, $ftpUser, $ftpPassword, $ftpPath) = $this->getUsersFTPConfig();

            $uploadSuccess = $ftpManager->sendFile($usersExportFilePath, $ftpPath . $remoteFilePath, $ftpHost, $ftpUser, $ftpPassword);

            if ($uploadSuccess === false)
                throw new \Exception(CustomMessagesConstants::ERROR_USERS_EXPORT_FILE_UPLOAD_FAILED);

            $logManager->logCustomMessage(CustomMessagesConstants::INFO_USERS_EXPORT_FILE_UPLOADED_SUCCESSFULLY);
            $console->writeLine(CustomMessagesConstants::INFO_USERS_EXPORT_FILE_UPLOADED_SUCCESSFULLY);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleCustomException($e, Logger::ERR, $console);
        }

    }

    public function maillistAction() {

        error_reporting(E_ERROR | E_PARSE);

        $console = $this->getConsole();

        try {

            $logManager = LogManager::getInstance($this->getServiceLocator());
            $customExportManager = CustomExportManager::getInstance($this->getServiceLocator());
            $ftpManager = FTPManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $logManager->logCustomMessage(CustomMessagesConstants::INFO_MAILLIST_EXPORT_STARTED);
            $console->writeLine("");
            $console->writeLine(CustomMessagesConstants::INFO_MAILLIST_EXPORT_STARTED);

            $maillistExportContent = $customExportManager->getMaillistExportContent();

            $maillistExportFileName = 'maillist_' . date('dmY') . '.csv';
            $maillistExportFilePath = $exportManager->saveExportFile($maillistExportFileName, $maillistExportContent);
            if ($maillistExportFilePath === false)
                throw new \Exception(CustomMessagesConstants::ERROR_MAILLIST_EXPORT_FILE_SAVING_FAILED);

            $logManager->logCustomMessage(CustomMessagesConstants::INFO_MAILLIST_EXPORT_FILE_SAVED_SUCCESSFULLY);
            $console->writeLine(CustomMessagesConstants::INFO_MAILLIST_EXPORT_FILE_SAVED_SUCCESSFULLY);

            $remoteFilePath = $maillistExportFileName;

            list($ftpHost, $ftpUser, $ftpPassword, $ftpPath) = $this->getMaillistFTPConfig();

            $uploadSuccess = $ftpManager->sendFile($maillistExportFilePath, $ftpPath. $remoteFilePath, $ftpHost, $ftpUser, $ftpPassword);

            if ($uploadSuccess === false)
                throw new \Exception(CustomMessagesConstants::ERROR_MAILLIST_EXPORT_FILE_UPLOAD_FAILED);

            $logManager->logCustomMessage(CustomMessagesConstants::INFO_MAILLIST_EXPORT_FILE_UPLOADED_SUCCESSFULLY);
            $console->writeLine(CustomMessagesConstants::INFO_MAILLIST_EXPORT_FILE_UPLOADED_SUCCESSFULLY);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleCustomException($e, Logger::ERR, $console);
        }

    }

    public function combinedAction() {

        ini_set('max_execution_time', 0);
        ini_set('max_input_time', -1);
        ini_set('memory_limit', -1);

        error_reporting(E_ERROR | E_PARSE);

        $console = $this->getConsole();

        try {

            $customExportManager = CustomExportManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $console->writeLine("");
            $console->writeLine("Start!");

            $combinedExportContent = $customExportManager->getCombinedExportContent();

            $combinedExportFileName = 'combined_' . date('dmY') . '.csv';
            $combinedExportFilePath = $exportManager->saveExportFile($combinedExportFileName, $combinedExportContent);
            if ($combinedExportFilePath === false)
                throw new \Exception("Fail!");

            $console->writeLine("Done!");

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
        return $this->getFTPConfig('users-export-ftp');
    }

    private function getMaillistFTPConfig() {
        return $this->getFTPConfig('maillist-export-ftp');
    }

    private function getFTPConfig($key) {
        $config = $this->getServiceLocator()->get('config');
        if (!empty($config) && array_key_exists($key, $config) && is_array($config[$key])) {
            $ftpConfig = $config[$key];
            if (array_key_exists('host', $ftpConfig) && array_key_exists('user', $ftpConfig) && array_key_exists('password', $ftpConfig) && array_key_exists('path', $ftpConfig))
                return array($ftpConfig['host'], $ftpConfig['user'], $ftpConfig['password'], $ftpConfig['path']);
        }
        throw new \Exception(CustomMessagesConstants::ERROR_EXPORT_FTP_WRONG_CONFIG);
    }

}
