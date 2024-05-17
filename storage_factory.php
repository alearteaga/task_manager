<?php

require_once 'mysql_storage.php';
require_once 'json_storage.php';
require_once 'csv_storage.php';

class StorageFactory {
    public static function createStorage($type, $config) {
        switch ($type) {
            case 'mysql':
                return new MySQLStorage($config);
            case 'json':
                return new JSONStorage($config);
            case 'csv':
                return new CSVStorage($config);
            default:
                throw new Exception("Tipo de almacenamiento no soportado: $type");
        }
    }
}

?>
