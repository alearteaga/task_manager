<?php

function connect() {
    $file = $_SERVER['HOME'] . '/.config/task-manager.sqlite3';
    
    $db = new SQLite3($file);
    if (!$db) {
        echo "Error: No se pudo establecer la conexión con la base de datos SQLite.\n";
        return null;
    }
    
    $query = "CREATE TABLE IF NOT EXISTS tasks (id INTEGER PRIMARY KEY, nombre TEXT, descripcion TEXT, estado TEXT)";
    $result = $db->exec($query);
    if ($result === false) {
        echo "Error: No se pudo crear la tabla 'tasks'.\n";
        $db->close();
        return null;
    }
    
    return $db;
}

function saveTask($task) {
    $db = connect();
    if (!$db) {
        return false;
    }
    
    $nombre = SQLite3::escapeString($task['nombre']);
    $descripcion = SQLite3::escapeString($task['descripcion']);
    
    $query = "INSERT INTO tasks (nombre, descripcion, estado) VALUES ('$nombre', '$descripcion', 'pendiente')";
    $result = $db->exec($query);
    if ($result === false) {
        echo "Error: No se pudo insertar la tarea en la base de datos SQLite.\n";
        $db->close();
        return false;
    }
    
    $db->close();
    return true;
}

function loadTasks() {
    $db = connect();
    if (!$db) {
        return [];
    }
    
    $tasks = [];
    $query = "SELECT * FROM tasks";
    $result = $db->query($query);
    if ($result === false) {
        echo "Error: No se pudieron cargar las tareas desde la base de datos SQLite.\n";
        $db->close();
        return [];
    }
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $tasks[] = $row;
    }
    
    $db->close();
    return $tasks;
}

?>
