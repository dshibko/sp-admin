<?php

namespace Neoco\View\Helper;

use ZfcRbac\Service\Rbac as RbacService;
use Zend\View\Helper\AbstractHelper;

class GetConstant extends AbstractHelper
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param  string $key
     * @return string
     */
    public function __invoke($key)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : '';
    }
}