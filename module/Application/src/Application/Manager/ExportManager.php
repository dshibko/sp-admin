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

    /**
     * @param array $dataArray
     * @param array $config
     * @param array $aliasConfig
     * @return string
     */
    public function exportArrayToCSV(array $dataArray, array $config, array $aliasConfig = array()) {
        $rows = array();

        $columns = array();
        foreach (array_keys($config) as $column)
            if (array_key_exists($column, $aliasConfig))
                $columns [] = $aliasConfig[$column];
            else
                $columns [] = $column;
        $rows [] = implode(',', $columns);
        foreach ($dataArray as $dataRow) {
            $cols = array();
            foreach ($config as $columnName => $columnType) {
                $value = "";
                if ($dataRow[$columnName] !== null && $dataRow[$columnName] !== '')
                    if (is_array($columnType)) {
                        $type = array_shift(array_keys($columnType));
                        $typeConfig = $columnType[$type];
                        switch ($type) {
                            case 'date':
                                $value = "\"" . str_replace("\"", "\"\"", $dataRow[$columnName]->format($typeConfig)) . "\"";
                                break;
                            case 'array':
                                $value = "\"" . str_replace("\"", "\"\"", $dataRow[$columnName][$typeConfig]) . "\"";
                                break;
                        }
                    } else {
                        switch ($columnType) {
                            case 'string':
                                $value = "\"" . str_replace("\"", "\"\"", $dataRow[$columnName]) . "\"";
                                break;
                            case 'number':

                                $value = ($dataRow[$columnName] !== false) ? $dataRow[$columnName] : 0;
                                break;
                            case 'array':
                                $value = "\"" . str_replace("\"", "\"\"", implode(",", $dataRow[$columnName])) . "\"";
                                break;
                        }
                    }
                $cols [] = $value;
            }
            $rows [] = implode(",", $cols);
        }
        return implode("\n", $rows);
    }

    /**
     * @param string $fileName
     * @param string $fileContent
     * @return bool
     */
    public function saveExportFile($fileName, $fileContent) {

        $exportDir = getcwd() . DIRECTORY_SEPARATOR . 'data'  . DIRECTORY_SEPARATOR . 'export'  . DIRECTORY_SEPARATOR;

        return file_put_contents($exportDir . $fileName, $fileContent) !== false ? $exportDir . $fileName : false;

    }

}
