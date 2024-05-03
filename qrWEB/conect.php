<?php

$hostname = "localhost";
$username = "worldsof_OME";
$password = "Wss1593.";
$database = "worldsof_qrFacil";

$conexion = mysqli_connect($hostname, $username, $password, $database);

if (!$conexion) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

?>
