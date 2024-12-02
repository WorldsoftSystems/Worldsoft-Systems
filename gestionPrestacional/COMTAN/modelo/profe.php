<?php
require_once '../conexion/conexion.php';

function agregarProfesor($nombre, $apellido, $especialidad, $prof_g)
{
    global $conn;

    // Si $prof_g es NULL, se asegura de que la columna acepte un valor NULL
    if (is_null($prof_g)) {
        // En este caso, se pasa un valor NULL explícito en la consulta
        $prof_g = NULL;
    }

    // Consulta preparada, siempre con 4 parámetros
    $sql = "INSERT INTO prof (nombre, apellido, especialidad, prof_generador) VALUES (?, ?, ?, ?)";

    // Prepara la consulta
    $stmt = $conn->prepare($sql);

    // El tipo de parámetros es siempre "sss" para los tres primeros valores y "i" para el último (prof_generador)
    $stmt->bind_param("ssss", $nombre, $apellido, $especialidad, $prof_g); 

    // Ejecuta la consulta
    $result = $stmt->execute();

    // Manejo de errores
    if (!$result) {
        error_log("Error al agregar el profesor: " . $stmt->error);
    }

    // Cierra el statement
    $stmt->close();

    return $result;
}




function eliminarProfesor($id)
{
    global $conn;
    $sql = "DELETE FROM prof WHERE cod_prof=$id";
    return $conn->query($sql);
}

function actualizarProfesor($id, $nombre, $apellido, $especialidad, $prof_g) {
    global $conn;
    $sql = "UPDATE prof SET nombre='$nombre', apellido='$apellido', especialidad='$especialidad', prof_generador = '$prof_g' WHERE cod_prof=$id";
    return $conn->query($sql);
}

?>