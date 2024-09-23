<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "qr";

/*
QR FACIL
$hostname = "localhost";
$username = "worldsof_OME";
$password = "Wss1593.";
$database = "worldsof_qr_facil";
*/

$conexion = mysqli_connect($hostname, $username, $password, $database);

if (!$conexion) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

?>
