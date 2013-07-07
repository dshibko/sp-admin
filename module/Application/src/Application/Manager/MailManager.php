<?php

namespace Application\Manager;

use Zend\Log\Logger;
use \Zend\Mail\Transport\SmtpOptions;
use \Zend\Mail\Transport\Smtp;
use \Zend\Mail\Transport\Sendmail;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Message;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use \Neoco\Manager\BasicManager;

class MailManager extends BasicManager {

    const ADMINS_CONFIG_KEY = 'admins_emails';

    const PASSWORD_RECOVERY_TEMPLATE = 'recovery';
    const PASSWORD_RECOVERY_EMAIL_SUBJECT = 'Password reset request for %s';

    const WELCOME_EMAIL_SUBJECT = 'Welcome to %s';
    const WELCOME_EMAIL_TEMPLATE = 'welcome';

    const HELP_AND_SUPPORT_EMAIL_TEMPLATE = 'help_and_support';
    const HELP_AND_SUPPORT_EMAIL_SUBJECT = 'Support Email';

    const NEW_ADMIN_EMAIL_SUBJECT = 'New Admin Account';
    const NEW_ADMIN_EMAIL_TEMPLATE = 'new_admin';

    const EXCEPTION_EMAIL_SUBJECT = 'Opta Exception [%s]';
    const EXCEPTION_EMAIL_TEMPLATE = 'exception';

    /**
     * @var MailManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MailManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new MailManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function sendPasswordRecoveryEmail($email, $hash, $fromAdmin = false) {
        $router = $this->getServiceLocator()->get('router');
        $route = $fromAdmin ? 'admin-forgot' : 'reset';
        $url = $router->assemble(array('hash' => $hash), array('name' => $route, 'force_canonical' => true));
        return $this->sendEmail($email, sprintf(self::PASSWORD_RECOVERY_EMAIL_SUBJECT, $this->getAppName()), self::PASSWORD_RECOVERY_TEMPLATE, array('url' => $url));
    }

    public function sendWelcomeEmail($email, $userName) {
        return $this->sendEmail($email, sprintf(self::WELCOME_EMAIL_SUBJECT, $this->getAppName()), self::WELCOME_EMAIL_TEMPLATE, array(
            'appName' => $this->getAppName(),
            'userName' => $userName
        ));
    }

    public function sendHelpAndSupportEmail($userEmail, $userName,$userMessage) {
        $helpAndSupportEmailAddress = $this->getHelpAndSupportEmailAddress();
        if (!empty($helpAndSupportEmailAddress)){
           $this->sendEmail($helpAndSupportEmailAddress,self::HELP_AND_SUPPORT_EMAIL_SUBJECT, self::HELP_AND_SUPPORT_EMAIL_TEMPLATE, array(
                'userMessage' => $userMessage,
                'userName'    => $userName,
                'userEmail'   => $userEmail
            ));
        }
        return true;
    }

    public function sendNewAdminEmail($email, $password) {
        $router = $this->getServiceLocator()->get('router');
        $this->sendEmail($email,self::NEW_ADMIN_EMAIL_SUBJECT, self::NEW_ADMIN_EMAIL_TEMPLATE, array(
            'password' => $password,
            'email'    => $email,
            'link'   => $router->assemble(array(),array('name' => 'admin-login', 'force_canonical' => true))
        ));
    }

    public function sendExceptionEmail(\Exception $e, $priority = Logger::ERR) {
        $config = $this->getServiceLocator()->get('config');
        if (array_key_exists(self::ADMINS_CONFIG_KEY, $config) && is_array($config[self::ADMINS_CONFIG_KEY])) {
            switch ($priority) {
                case Logger::EMERG:
                    $priority = 'Emergency';
                    break;
                case Logger::CRIT:
                    $priority = 'Critical';
                    break;
                default:
                    $priority = 'Error';
            }
            $this->sendEmail($config[self::ADMINS_CONFIG_KEY], sprintf(self::EXCEPTION_EMAIL_SUBJECT, $priority), self::EXCEPTION_EMAIL_TEMPLATE, array(
                'priority' => $priority,
                'message'    => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ));
        }
    }

    private function sendEmail($to, $subject, $template, $params = array()) {
        $content = $this->prepareContent($template, $params);
        $message = $this->prepareMessage($to, $subject, $content);
        $transport = $this->prepareTransport();
        $transport->send($message);
        return true;
    }

    private function prepareTransport() {
       /* $transport = new Smtp();
        $options   = new SmtpOptions();
        $options->setHost('smtp.gmail.com')
            ->setConnectionClass('login')
            ->setName('smtp.gmail.com')
            ->setPort(587)
            ->setConnectionConfig(array(
                'username' => '',
                'password' => '',
                'ssl' => 'tls',
            ));
        $transport->setOptions($options);
        return $transport;*/
       return new Sendmail();
    }

    private function prepareMessage($to, $subject, $content) {
        $message = new Message();
        $config = $this->getEmailConfig();

        $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->setParts(array($html));

        $message->addFrom($config['fromEmail'], $config['fromName'])
            ->setSubject($subject)
            ->setBody($body)
            ->setEncoding("UTF-8");
        if (!is_array($to)) $to = array($to);
        foreach ($to as $t)
            $message->addTo($t);

        return $message;
    }

    private function prepareContent($template, $params = array()) {
        $view = new \Zend\View\Renderer\PhpRenderer();
        $resolver = new \Zend\View\Resolver\TemplateMapResolver();
        $resolver->setMap(array(
            'mailLayout' => __DIR__ . '/../../../../../module/Application/view/layout/layout-email.phtml',
            'mailTemplate' => __DIR__ . '/../../../../../module/Application/view/email/' . $template . '.phtml',
        ));
        $view->setResolver($resolver);

        $params['appName'] = $this->getAppName();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('mailTemplate')
            ->setVariables($params);

        $content = $view->render($viewModel);

        $viewLayout = new \Zend\View\Model\ViewModel();
        $viewLayout->setTemplate('mailLayout')
            ->setVariables(array(
            'content' => $content,
            'appName' => $this->getAppName()
        ));

        return $view->render($viewLayout);
    }

    /**
     * @var string
     */
    private $appName;

    /**
     * @return string
     */
    private function getAppName() {
        if ($this->appName == null) {
            $config = $this->getServiceLocator()->get('config');
            $this->appName = array_key_exists('app_name', $config) ? $config['app_name'] : 'Score Predictor';
        }
        return $this->appName;
    }

    /**
     * @var array
     */
    private $emailConfig;

    /**
     * @return array
     */
    private function getEmailConfig() {
        if ($this->emailConfig == null) {
            $config = $this->getServiceLocator()->get('config');
            $this->emailConfig = array_key_exists('email', $config) ? $config['email'] : array();
        }
        return $this->emailConfig;
    }

    private function getHelpAndSupportEmailAddress()
    {
        return SettingsManager::getInstance($this->getServiceLocator())->getSetting(SettingsManager::HELP_AND_SUPPORT_EMAIL);
    }

}
