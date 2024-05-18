<?php

require_once 'mysql_storage.php';
require_once 'json_storage.php';
require_once 'csv_storage.php';

class StorageFactory {
    public static function create($config) {
        $storageType = $config['Main']['storage-type'] ?? null;
        if (!$storageType) {
            throw new Exception("Tipo de almacenamiento no definido.");
        }

        switch ($storageType) {
            case 'mysql':
                if (!isset($config['MySQL'])) {
                    throw new Exception("Configuración de MySQL no encontrada.");
                }
                return new MySQLStorage($config['MySQL']);
            case 'json':
                if (!isset($config['json'])) {
                    throw new Exception("Configuración de JSON no encontrada.");
                }
                return new JSONStorage($config['json']);
            case 'csv':
                if (!isset($config['csv'])) {
                    throw new Exception("Configuración de CSV no encontrada.");
                }
                return new CSVStorage($config['csv']);
            default:
                throw new Exception("Tipo de almacenamiento no soportado: $storageType");
        }
    }
}

?>
