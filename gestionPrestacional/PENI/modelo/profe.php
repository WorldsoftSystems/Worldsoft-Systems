<?php
require_once '../conexion/conexion.php';

function agregarProfesor($nombre, $apellido, $especialidad, $prof_g)
{
    global $conn;
    $sql = "INSERT INTO prof (nombre, apellido, especialidad,prof_generador ) VALUES ('$nombre', '$apellido', '$especialidad','$prof_g')";
    return $conn->query($sql);
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