<?php
require_once '../conexion/conexion.php';

function agregarProfesor($nombre, $apellido, $especialidad)
{
    global $conn;
    $sql = "INSERT INTO prof (nombre, apellido, especialidad) VALUES ('$nombre', '$apellido', '$especialidad')";
    return $conn->query($sql);
}

function eliminarProfesor($id)
{
    global $conn;
    $sql = "DELETE FROM prof WHERE cod_prof=$id";
    return $conn->query($sql);
}

function actualizarProfesor($id, $nombre, $apellido, $especialidad) {
    global $conn;
    $sql = "UPDATE prof SET nombre='$nombre', apellido='$apellido', especialidad='$especialidad' WHERE cod_prof=$id";
    return $conn->query($sql);
}

?>