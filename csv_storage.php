<?php

require_once 'storage.php';

class CSVStorage implements Storage {
    private $filePath;

    public function __construct($config) {
        if (!isset($config['file'])) {
            throw new Exception("No se ha definido el archivo CSV en la configuración.");
        }
        $this->filePath = $config['file'];
    }

    public function getTasks() {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $file = fopen($this->filePath, 'r');
        $tasks = [];
        while (($data = fgetcsv($file)) !== FALSE) {
            $tasks[] = [
                'id' => $data[0],
                'name' => $data[1],
                'description' => $data[2],
                'state' => $data[3],
            ];
        }
        fclose($file);
        return $tasks;
    }

    public function addTask($task) {
        $file = fopen($this->filePath, 'a');
        $id = count($this->getTasks()) + 1;
        fputcsv($file, [$id, $task['name'], $task['description'], $task['state']]);
        fclose($file);
    }

    public function completeTask($id) {
        $tasks = $this->getTasks();
        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['state'] = 'completado';
                break;
            }
        }
        $this->writeTasks($tasks);
    }

    public function removeTask($id) {
        $tasks = $this->getTasks();
        $tasks = array_filter($tasks, function($task) use ($id) {
            return $task['id'] != $id;
        });
        $this->writeTasks($tasks);
    }

    private function writeTasks($tasks) {
        $file = fopen($this->filePath, 'w');
        foreach ($tasks as $task) {
            fputcsv($file, [$task['id'], $task['name'], $task['description'], $task['state']]);
        }
        fclose($file);
    }
}

?>


