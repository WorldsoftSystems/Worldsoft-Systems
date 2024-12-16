<?php
require_once "../../conexion.php";

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consultar todos los usuarios
$sql = "SELECT id, usuario, clave FROM usuarios"; // Incluye la columna de clave (contraseña hasheada)
$result = $conexion->query($sql);

$usuarios = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

$conexion->close();

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($usuarios);
?>
