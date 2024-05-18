<?php

require_once 'storage.php';

class JSONStorage implements Storage {
    private $filePath;

    public function __construct($config) {
        if (!isset($config['file'])) {
            throw new Exception("No se ha definido el archivo JSON en la configuración.");
        }
        $this->filePath = $config['file'];
    }

    public function getTasks() {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $json = file_get_contents($this->filePath);
        return json_decode($json, true);
    }

    public function addTask($task) {
        $tasks = $this->getTasks();
        $task['id'] = count($tasks) + 1;
        $tasks[] = $task;
        file_put_contents($this->filePath, json_encode($tasks, JSON_PRETTY_PRINT));
    }

    public function completeTask($id) {
        $tasks = $this->getTasks();
        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['state'] = 'completado';
                break;
            }
        }
        file_put_contents($this->filePath, json_encode($tasks, JSON_PRETTY_PRINT));
    }

    public function removeTask($id) {
        $tasks = $this->getTasks();
        $tasks = array_filter($tasks, function($task) use ($id) {
            return $task['id'] != $id;
        });
        file_put_contents($this->filePath, json_encode(array_values($tasks), JSON_PRETTY_PRINT));
    }
}

?>
