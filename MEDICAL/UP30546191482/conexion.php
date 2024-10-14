<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "medical_pq000";

// Crear conexión
$conn = new mysqli($hostname, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}
?>
