<?php

namespace Application\Manager;

use \Zend\Log\Writer\Stream;
use \Zend\Log\Logger;
use \Application\Model\Helpers\MessagesConstants;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ExportManager extends BasicManager {

    /**
     * @var ExportManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ExportManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ExportManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function exportArrayToCSV(array $dataArray, array $config) {
        $rows = array();
        $rows [] = implode(',', array_keys($config));
        foreach ($dataArray as $dataRow) {
            $cols = array();
            foreach ($config as $columnName => $columnType) {
                $value = "";
                if (is_array($columnType)) {
                    $type = array_shift(array_keys($columnType));
                    $typeConfig = $columnType[$type];
                    switch ($type) {
                        case 'date':
                            $value = "\"" . $dataRow[$columnName]->format($typeConfig) . "\"";
                            break;
                        case 'array':
                            $value = "\"" . $dataRow[$columnName][$typeConfig] . "\"";
                            break;
                    }
                } else {
                    switch ($columnType) {
                        case 'string':
                            $value = "\"" . $dataRow[$columnName] . "\"";
                            break;
                        case 'number':
                            $value = $dataRow[$columnName];
                            break;
                    }
                }
                $cols [] = $value;
            }
            $rows [] = implode(",", $cols);
        }
        return implode("\n", $rows);
    }

}
