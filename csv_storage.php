<?php

class CSVStorage {
    private $filePath;

    public function __construct($config) {
        $this->filePath = $config['csv']['file'];
    }

    private function readData() {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $file = fopen($this->filePath, 'r');
        $tasks = [];
        while (($data = fgetcsv($file)) !== FALSE) {
            $tasks[] = ['id' => $data[0], 'name' => $data[1], 'description' => $data[2], 'state' => $data[3]];
        }
        fclose($file);
        return $tasks;
    }

    private function writeData($data) {
        $file = fopen($this->filePath, 'w');
        foreach ($data as $task) {
            fputcsv($file, $task);
        }
        fclose($file);
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
