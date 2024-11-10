<?php
/*
$hostname = "localhost";
$username = "worldsof_OME";
$password = "Wss1593.";
$database = "worldsof_medical_prueba";
*/

$hostname = "localhost";
$username = "root";
$password = "";
$database = "medical_test"; 


// Crear conexión
$conn = new mysqli($hostname, $username, $password, $database);
// Establecer la codificación de caracteres
$conn->set_charset("utf8mb4");
// Verificar conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}
?>
