<?php
/*
PEÑI
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worldsof_gestion_prestacional_peni";
*/


$servername = "localhost";
$username = "worldsof_OME";
$password = "Wss1593.";
$dbname = "worldsof_gestion_prestacional_peni"; 



// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>
