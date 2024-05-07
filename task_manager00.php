<?php

if (php_sapi_name() != 'cli') {
    die("Error : Este script solo funciona en CLI\n");
}

function actionBBDD($action) {
    $servername = "localhost";
    $database = "basedatosA";
    $username = "root";
    $password = "ubuntu09";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        echo "\nConnected successfully\n";
    }
    switch ($action) {
        case 'addTask':
            $nombre = readline("Ingresa un nombre tarea : ");
            $descripcion = readline("Explica la tarea: ");
            $sql_query=$conn -> query("INSERT INTO tasks (nombre, descripcion) VALUES ('$nombre', '$descripcion')");
            if ($sql_query === TRUE) {
                echo "\nTarea añadida a base de datos\n";
            }
            break;
        case 'removeTask':
            $id = readline("Ingresa la ID de la tarea : ");
            $sql_query=$conn->query("DELETE FROM tasks WHERE id=$id");
            if ($sql_query === TRUE) {
                echo "\nTarea borrada\n";
            }
            break;
        case 'listTasks':
            $searchAll = $conn -> query("SELECT * FROM tasks");
            echo "====== TAREAS ======\n";
            while($task=$searchAll->fetch_assoc()) {
                echo "\n";
                echo "ID : ",$task["id"],"\n";
                echo "Nombre : ",$task["nombre"],"\n";
                echo "Descripcion : ",$task["descripcion"],"\n";
                echo "Estado : ",$task["estado"],"\n";
                echo "===========================================\n";
            }
            break;
        case 'completeTask':
            $id = readline("Ingresa la ID de la tarea : ");
            $sql_query=$conn->query("UPDATE tasks SET estado='completada' WHERE id=$id");
            if ($sql_query === TRUE) {
                echo "\nTarea completada\n";
            }
            break;
        default:
            echo "Error : No existe la acción "+$action+"\n";
            
            break;
    }
    mysqli_close($conn);
}

// Función para mostrar el menú de opciones
function showMenu() {
    echo "=========== Gestor de tareas ===========\n";
    echo "1. Mostrar tareas\n";
    echo "2. Añadir tarea\n";
    echo "3. Marcar tarea como completada\n";
    echo "4. Eliminar tarea\n";
    echo "5. Salir\n";
    echo "==============================\n";
}

while (true) {
    showMenu();
    echo "Seleccione una opción: ";
    $option = readline();

    switch ($option) {
        case '1':
            //showTasks($tasks);
            actionBBDD("listTasks");
            break;
        case '2':
            actionBBDD("addTask");
            break;
        case '3':
            actionBBDD("completeTask");
            break;
        case '4':
            actionBBDD("removeTask");
            break;
        case '5':
            echo "¡Adiós!\n";
            exit;
        default:
            echo "Opción no válida. Por favor, seleccione una opción válida.\n";
    }
}
 

?>

