<?php

require_once 'storage_csv.php';
require_once 'storage_json.php';
require_once 'storage_sqlite.php';
require_once 'storage_mysql.php';

function checkFirstRun() {
    try {
        $configFile = $_SERVER['HOME'] . '/.config/task-manager.cfg';

        if (!file_exists($configFile)) {
            echo "¡Bienvenido! Parece que es la primera vez que ejecutas el programa.\n";
            echo "Por favor, elige cómo deseas almacenar tus tareas:\n";
            echo "1. Archivo CSV\n";
            echo "2. Archivo JSON\n";
            echo "3. Base de datos SQLite\n";
            echo "4. Base de datos MariaDB/MySQL\n";

            $choice = readline("Ingresa el número correspondiente a tu elección: ");

            switch ($choice) {
                case '1':
                    $storageType = 'csv';
                    break;
                case '2':
                    $storageType = 'json';
                    break;
                case '3':
                    $storageType = 'sqlite';
                    break;
                case '4':
                    $storageType = 'mysql';
                    break;
                default:
                    throw new Exception("Opción no válida. Saliendo.");
                    break;
            }

            saveConfig($storageType);
            echo "¡Configuración guardada! Ahora puedes comenzar a utilizar el gestor de tareas.\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

function saveConfig($storageType) {
    try {
        $configFile = $_SERVER['HOME'] . '/.config/task-manager.cfg';
        $config = "[Main]\nstorage-type = $storageType\n";

        file_put_contents($configFile, $config);
    } catch (Exception $e) {
        throw new Exception("Error al guardar la configuración: " . $e->getMessage());
    }
}

function getConfig() {
    try {
        $configFile = $_SERVER['HOME'] . '/.config/task-manager.cfg';

        if (file_exists($configFile)) {
            $config = parse_ini_file($configFile);
            return $config['storage-type'];
        } else {
            return null;
        }
    } catch (Exception $e) {
        throw new Exception("Error al obtener la configuración: " . $e->getMessage());
    }
}

function actionBBDD($action) {
    try {
        $storageType = getConfig();

        switch ($storageType) {
            case 'csv':
                actionCsv($action);
                break;
            case 'json':
                actionJson($action);
                break;
            case 'sqlite':
                actionSqlite($action);
                break;
            case 'mysql':
                actionMysql($action);
                break;
            default:
                throw new Exception("Tipo de almacenamiento no válido.");
                break;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

function showMenu() {
    echo "=========== Gestor de tareas ===========\n";
    echo "1. Mostrar tareas\n";
    echo "2. Añadir tarea\n";
    echo "3. Marcar tarea como completada\n";
    echo "4. Eliminar tarea\n";
    echo "5. Salir\n";
    echo "==============================\n";
}

checkFirstRun();

while (true) {
    showMenu();
    echo "Seleccione una opción: ";
    $option = readline();

    switch ($option) {
        case '1':
            actionBBDD("listTasks");
            break;
        case '2':
            actionBBDD("addTask");
            break;
        case '3':
            actionBBDD("completeTask");
            break;
        case '4':
            actionBBDD("removeTask");
            break;
        case '5':
            echo "¡Adiós!\n";
            exit;
        default:
            echo "Opción no válida. Por favor, seleccione una opción válida.\n";
    }
}

?>
