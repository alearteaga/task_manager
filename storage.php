<?php

interface Storage {
    public function getTasks();
    public function addTask($task);
    public function completeTask($id);
    public function removeTask($id);
}

?>
