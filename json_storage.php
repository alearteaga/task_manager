<?php

class JSONStorage {
    private $filePath;

    public function __construct($config) {
        $this->filePath = $config['json']['file'];
    }

    private function readData() {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $jsonData = file_get_contents($this->filePath);
        return json_decode($jsonData, true) ?: [];
    }

    private function writeData($data) {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function getTasks() {
        return $this->readData();
    }

    public function addTask($task) {
        $tasks = $this->readData();
        $task['id'] = uniqid();
        $tasks[] = $task;
        $this->writeData($tasks);
    }

    public function completeTask($id) {
        $tasks = $this->readData();
        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['state'] = 'completada';
                break;
            }
        }
        $this->writeData($tasks);
    }

    public function removeTask($id) {
        $tasks = $this->readData();
        $tasks = array_filter($tasks, function($task) use ($id) {
            return $task['id'] != $id;
        });
        $this->writeData($tasks);
    }
}

?>
