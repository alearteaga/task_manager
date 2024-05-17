<?php

if (php_sapi_name() != 'cli') {
    die("Error: Este script solo funciona en CLI\n");
}

class MySQLStorage {
    private $conn;

    public function __construct($config) {
        $servername = $config['MySQL']['host'];
        $database = $config['MySQL']['db'];
        $username = $config['MySQL']['user'];
        $password = $config['MySQL']['password'];
        $port = $config['MySQL']['port'];

        $this->conn = mysqli_connect($servername, $username, $password, $database, $port);

        if (!$this->conn) {
            die("Conexión fallida: " . mysqli_connect_error());
        } else {
            echo "\nConexión establecida correctamente\n";
        }
    }

    public function getTasks() {
        $tasks = [];
        $result = $this->conn->query("SELECT * FROM tareas");
        while ($task = $result->fetch_assoc()) {
            $tasks[] = $task;
        }
        return $tasks;
    }

    public function addTask($task) {
        $name = $task['name'];
        $description = $task['description'];
        $sql_query = $this->conn->query("INSERT INTO tareas (nombre, descripcion, estado) VALUES ('$name', '$description', 'pendiente')");
        if ($sql_query === TRUE) {
            echo "\nTarea agregada a la base de datos\n";
        }
    }

    public function completeTask($id) {
        $sql_query = $this->conn->query("UPDATE tareas SET estado='completada' WHERE id=$id");
        if ($sql_query === TRUE) {
            echo "\nTarea completada\n";
        }
    }

    public function removeTask($id) {
        $sql_query = $this->conn->query("DELETE FROM tareas WHERE id=$id");
        if ($sql_query === TRUE) {
            echo "\nTarea eliminada\n";
        }
    }

    public function __destruct() {
        mysqli_close($this->conn);
    }
}

?>
