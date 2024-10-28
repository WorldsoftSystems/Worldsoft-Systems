<?php

$hostname = "localhost";
$username = "worldsof_OME";
$password = "Wss1593.";
$database = "worldsof_medical_pq0328";

/*$hostname = "localhost";
$username = "root";
$password = "";
$database = "medical_pq000"; */

// Crear conexión
$conn = new mysqli($hostname, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}
?>
