<?php

namespace Application\Manager;

use \Zend\Mail\Transport\SmtpOptions;
use \Zend\Mail\Transport\Smtp;
use \Zend\Mail\Transport\Sendmail;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Message;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use \Neoco\Manager\BasicManager;

class MailManager extends BasicManager {

    const PASSWORD_RECOVERY_TEMPLATE = 'recovery';
    const PASSWORD_RECOVERY_EMAIL_SUBJECT = 'Password reset request for %s';
    const WELCOME_EMAIL_SUBJECT = 'Welcome to %s';
    const WELCOME_EMAIL_TEMPLATE = 'welcome';

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

    public function sendWelcomeEmail($email, $userName)
    {
        return $this->sendEmail($email, sprintf(self::WELCOME_EMAIL_SUBJECT, $this->getAppName()), self::WELCOME_EMAIL_TEMPLATE, array(
            'appName' => $this->getAppName(),
            'userName' => $userName
        ));
    }
    private function sendEmail($to, $subject, $template, $params = array()) {
        $content = $this->prepareContent($template, $params);
        $message = $this->prepareMessage($to, $subject, $content);
        $transport = $this->prepareTransport();
        $transport->send($message);
        return true;
    }

    private function prepareTransport() {
//        $transport = new Smtp();
//        $options   = new SmtpOptions(array(
//            'name'              => 'localhost.localdomain',
//            'host'              => '127.0.0.1',
//            'connection_class'  => 'login',
//            'connection_config' => array(
//                'username' => 'user',
//                'password' => 'pass',
//            ),
//        ));
//        $transport->setOptions($options);
//        return $transport;
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
            ->addTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setEncoding("UTF-8");
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

}
