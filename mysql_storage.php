<?php

require_once 'storage.php';

class MySQLStorage implements Storage {
    private $connection;

    public function __construct($config) {
        $this->connection = new mysqli(
            $config['host'], $config['user'], $config['password'], $config['db'], $config['port']
        );
        if ($this->connection->connect_error) {
            throw new Exception("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getTasks() {
        $result = $this->connection->query("SELECT id, name, description, state FROM tasks");
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        return $tasks;
    }

    public function addTask($task) {
        $stmt = $this->connection->prepare("INSERT INTO tasks (name, description, state) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $task['name'], $task['description'], $task['state']);
        $stmt->execute();
    }

    public function completeTask($id) {
        $stmt = $this->connection->prepare("UPDATE tasks SET state = 'completado' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function removeTask($id) {
        $stmt = $this->connection->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

?>
