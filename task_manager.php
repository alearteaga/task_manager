<?php

require_once 'mysql_storage.php';
require_once 'json_storage.php';
require_once 'csv_storage.php';
require_once 'storage_factory.php';

$configFile = $_SERVER['HOME'] . '/.config/task_manager.cfg';
$storage = null;

if (file_exists($configFile)) {
    $config = parse_ini_file($configFile, true);
    $storageType = $config['Main']['storage-type'];

    try {
        $storage = StorageFactory::createStorage($storageType, $config);
    } catch (Exception $e) {
        die($e->getMessage());
    }
} else {
    echo "Configuración no encontrada. Creando archivo de configuración...\n";
    echo "Elige un tipo de almacenamiento (mysql, json, csv): ";
    $storageType = readline();

    if ($storageType === 'mysql') {
        echo "Host: ";
        $host = readline();
        echo "Base de datos: ";
        $db = readline();
        echo "Usuario: ";
        $user = readline();
        echo "Contraseña: ";
        $password = readline();
        echo "Puerto (default 3306): ";
        $port = readline() ?: 3306;

        $configData = "[Main]\nstorage-type = $storageType\n\n";
        $configData .= "[MySQL]\nhost = $host\ndb = $db\nuser = $user\npassword = $password\nport = $port\n";
        file_put_contents($configFile, $configData);
    } else {
        echo "Introduce la ruta del archivo: ";
        $filePath = readline();

        $configData = "[Main]\nstorage-type = $storageType\n\n";
        $configData .= "[$storageType]\nfile = $filePath\n";
        file_put_contents($configFile, $configData);
    }

    try {
        $storage = StorageFactory::createStorage($storageType, $config);
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

function processOption($option, $storage) {
    switch ($option) {
        case '1':
            $tasks = $storage->getTasks();
            if (!empty($tasks)) {
                echo "======= Tareas =======\n";
                foreach ($tasks as $task) {
                    echo "ID: {$task['id']}\nNombre: {$task['name']}\nDescripción: {$task['description']}\nEstado: {$task['state']}\n";
                    echo "======================\n";
                }
            } else {
                echo "No se encontraron tareas.\n";
            }
            break;
        case '2':
            echo "Introduce el nombre de la tarea: ";
            $name = readline();
            echo "Introduce la descripción de la tarea: ";
            $description = readline();
            $task = ['name' => $name, 'description' => $description, 'state' => 'pendiente'];
            $storage->addTask($task);
            echo "Tarea añadida con éxito.\n";
            break;
        case '3':
            echo "Introduce el ID de la tarea para completar: ";
            $id = readline();
            $storage->completeTask($id);
            echo "Tarea completada con éxito.\n";
            break;
        case '4':
            echo "Introduce el ID de la tarea para eliminar: ";
            $id = readline();
            $storage->removeTask($id);
            echo "Tarea eliminada con éxito.\n";
            break;
        case '5':
            echo "¡Adiós!\n";
            exit;
        default:
            echo "Opción no válida. Por favor, selecciona una opción válida.\n";
    }
}

while (true) {
    echo "=========== Gestor de Tareas ===========\n";
    echo "1. Mostrar Tareas\n";
    echo "2. Añadir Tarea\n";
    echo "3. Completar Tarea\n";
    echo "4. Eliminar Tarea\n";
    echo "5. Salir\n";
    echo "========================================\n";
    echo "Selecciona una opción: ";
    $option = readline();
    processOption($option, $storage);
}

?>
