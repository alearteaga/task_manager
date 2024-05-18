<?php

require_once 'storage_factory.php';

$configFilePath = '/home/ale/.config/task_manager.cfg';
if (!file_exists($configFilePath)) {
    echo "Configuración no encontrada. Creando archivo de configuración...\n";
    $storageType = readline("Elija un tipo de almacenamiento (mysql, json, csv): ");
    $config = ['Main' => ['storage-type' => $storageType]];

    switch ($storageType) {
        case 'mysql':
            $config['MySQL'] = [
                'host' => readline("Ingrese el host: "),
                'db' => readline("Ingrese el nombre de la base de datos: "),
                'user' => readline("Ingrese el usuario: "),
                'password' => readline("Ingrese la contraseña: "),
                'port' => readline("Ingrese el puerto: "),
            ];
            break;
        case 'json':
            $config['json'] = ['file' => readline("Ingrese la ruta del archivo JSON: ")];
            break;
        case 'csv':
            $config['csv'] = ['file' => readline("Ingrese la ruta del archivo CSV: ")];
            break;
        default:
            die("Tipo de almacenamiento no soportado.\n");
    }

    file_put_contents($configFilePath, json_encode($config, JSON_PRETTY_PRINT));
} else {
    $config = json_decode(file_get_contents($configFilePath), true);
}

try {
    $storage = StorageFactory::create($config);
} catch (Exception $e) {
    die("Error al crear el almacenamiento: " . $e->getMessage() . "\n");
}

function mostrarMenu() {
    echo "=========== Gestor de tareas ===========\n";
    echo "1. Listar tareas\n";
    echo "2. Añadir tarea\n";
    echo "3. Completar tarea\n";
    echo "4. Eliminar tarea\n";
    echo "5. Salir\n";
    echo "========================================\n";
}

do {
    mostrarMenu();
    $option = readline("Elija una opción: ");
    switch ($option) {
        case 1:
            $tasks = $storage->getTasks();
            foreach ($tasks as $task) {
                echo "{$task['id']}. {$task['name']} - {$task['description']} [{$task['state']}]\n";
            }
            break;
        case 2:
            $name = readline("Ingrese el nombre de la tarea: ");
            $description = readline("Ingrese la descripción de la tarea: ");
            $task = ['name' => $name, 'description' => $description, 'state' => 'pendiente'];
            $storage->addTask($task);
            echo "Tarea añadida.\n";
            break;
        case 3:
            $id = readline("Ingrese el ID de la tarea a completar: ");
            $storage->completeTask($id);
            echo "Tarea completada.\n";
            break;
        case 4:
            $id = readline("Ingrese el ID de la tarea a eliminar: ");
            $storage->removeTask($id);
            echo "Tarea eliminada.\n";
            break;
        case 5:
            exit("Saliendo del gestor de tareas...\n");
        default:
            echo "Opción no válida.\n";
    }
} while (true);

?>