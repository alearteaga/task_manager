<?php

function connect() {
    $servername = "localhost";
    $username = "root";
    $password = "ubuntu09";
    $database = "basedatosA";
    
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        echo "Error: No se pudo establecer la conexión con la base de datos MySQL.\n";
        return null;
    }
    
    return $conn;
}

function saveTask($task) {
    $conn = connect();
    if (!$conn) {
        return false;
    }
    
    $nombre = $conn->real_escape_string($task['nombre']);
    $descripcion = $conn->real_escape_string($task['descripcion']);
    
    $query = "INSERT INTO tasks (nombre, descripcion, estado) VALUES ('$nombre', '$descripcion', 'pendiente')";
    $result = $conn->query($query);
    if ($result === false) {
        echo "Error: No se pudo insertar la tarea en la base de datos MySQL.\n";
        $conn->close();
        return false;
    }
    
    $conn->close();
    return true;
}

function loadTasks() {
    $conn = connect();
    if (!$conn) {
        return [];
    }
    
    $tasks = [];
    $query = "SELECT * FROM tasks";
    $result = $conn->query($query);
    if ($result === false) {
        echo "Error: No se pudieron cargar las tareas desde la base de datos MySQL.\n";
        $conn->close();
        return [];
    }
    
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    
    $conn->close();
    return $tasks;
}

?>
