<?php

function saveTask($task) {
    $file = $_SERVER['HOME'] . '/.config/task-manager.json';
    
    $tasks = loadTasks();
    $tasks[] = $task;
    
    $success = file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    if ($success === false) {
        echo "Error: No se pudo escribir en el archivo JSON.\n";
    }
    
    return $success;
}

function loadTasks() {
    $file = $_SERVER['HOME'] . '/.config/task-manager.json';
    
    if (!file_exists($file)) {
        echo "Error: El archivo JSON no existe.\n";
        return [];
    }
    
    $data = file_get_contents($file);
    if ($data === false) {
        echo "Error: No se pudo leer el archivo JSON.\n";
        return [];
    }
    
    $tasks = json_decode($data, true);
    if ($tasks === null) {
        echo "Error: No se pudo decodificar el archivo JSON.\n";
        return [];
    }
    
    return $tasks;
}

?>
