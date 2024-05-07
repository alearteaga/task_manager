<?php

if (php_sapi_name() != 'cli') {
    die("Error: Este script solo funciona en CLI\n");
}

function accionBBDD($accion) {
    $servername = "localhost";
    $database = "basedatosA";
    $username = "root";
    $password = "ubuntu09";
    // Crear conexión
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Comprobar conexión
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    } else {
        echo "\nConexión establecida correctamente\n";
    }
    switch ($accion) {
        case 'addTask':
            $nombre = readline("Ingrese un nombre de tarea: ");
            $descripcion = readline("Describa la tarea: ");
            $sql_query = $conn->query("INSERT INTO tareas (nombre, descripcion) VALUES ('$nombre', '$descripcion')");
            if ($sql_query === TRUE) {
                echo "\nTarea agregada a la base de datos\n";
            }
            break;
        case 'removeTask':
            $id = readline("Ingrese el ID de la tarea: ");
            $sql_query = $conn->query("DELETE FROM tareas WHERE id=$id");
            if ($sql_query === TRUE) {
                echo "\nTarea eliminada\n";
            }
            break;
        case 'listTasks':
            $buscarTodas = $conn->query("SELECT * FROM tareas");
            echo "====== TAREAS ======\n";
            while ($tarea = $buscarTodas->fetch_assoc()) {
                echo "\n";
                echo "ID: ", $tarea["id"], "\n";
                echo "Nombre: ", $tarea["nombre"], "\n";
                echo "Descripción: ", $tarea["descripcion"], "\n";
                echo "Estado: ", $tarea["estado"], "\n";
                echo "===========================================\n";
            }
            break;
        case 'completeTask':
            $id = readline("Ingrese el ID de la tarea: ");
            $sql_query = $conn->query("UPDATE tareas SET estado='completada' WHERE id=$id");
            if ($sql_query === TRUE) {
                echo "\nTarea completada\n";
            }
            break;
        default:
            echo "Error: La acción " . $accion . " no existe\n";
            break;
    }
    mysqli_close($conn);
}

// Función para mostrar el menú de opciones
function mostrarMenu() {
    echo "=========== Gestor de tareas ===========\n";
    echo "1. Mostrar tareas\n";
    echo "2. Agregar tarea\n";
    echo "3. Marcar tarea como completada\n";
    echo "4. Eliminar tarea\n";
    echo "5. Salir\n";
    echo "==============================\n";
}

$options = getopt('a:');

if (isset($options['a'])) {
    switch ($options['a']) {
        case 'addTask':
            accionBBDD("addTask");
            break;
        case 'removeTask':
            accionBBDD("removeTask");
            break;
        case 'listTasks':
            accionBBDD("listTasks");
            break;
        case 'completeTask':
            accionBBDD("completeTask");
            break;
        default:
            echo "Error: La acción " . $options['a'] . " no existe\n";
            break;
    }
} else {
    while (true) {
        mostrarMenu();
        echo "Seleccione una opción: ";
        $opcion = readline();

        switch ($opcion) {
            case '1':
                accionBBDD("listTasks");
                break;
            case '2':
                accionBBDD("addTask");
                break;
            case '3':
                accionBBDD("completeTask");
                break;
            case '4':
                accionBBDD("removeTask");
                break;
            case '5':
                echo "¡Adiós!\n";
                exit;
            default:
                echo "Opción no válida. Por favor, seleccione una opción válida.\n";
        }
    }
}

?>
