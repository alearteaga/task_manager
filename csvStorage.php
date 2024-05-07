<?php

function saveTask($task) {
    $file = $_SERVER['HOME'] . '/.config/task-manager.csv';
    
    $fp = fopen($file, 'a');
    if ($fp === false) {
        echo "Error: No se pudo abrir el archivo CSV para escritura.\n";
        return false;
    }
    
    $success = fputcsv($fp, $task);
    if ($success === false) {
        echo "Error: No se pudo escribir en el archivo CSV.\n";
    }
    
    fclose($fp);
    
    return $success;
}

function loadTasks() {
    $file = $_SERVER['HOME'] . '/.config/task-manager.csv';
    
    if (!file_exists($file)) {
        echo "Error: El archivo CSV no existe.\n";
        return [];
    }
    
    $tasks = [];
    $fp = fopen($file, 'r');
    if ($fp === false) {
        echo "Error: No se pudo abrir el archivo CSV para lectura.\n";
        return [];
    }
    
    while (($data = fgetcsv($fp)) !== false) {
        $tasks[] = $data;
    }
    
    fclose($fp);
    
    return $tasks;
}

?>
