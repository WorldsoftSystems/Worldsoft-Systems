<?php
session_start();
include('../../conect.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403); // No autorizado
    exit;
}

$username = $_SESSION['username'];

// Actualizar el conteo de QR
$query = "UPDATE usuarios_rosario SET cant_qr = cant_qr + 1 WHERE user = '$username'";
$result = mysqli_query($conexion, $query);

if ($result) {
    http_response_code(200); // Éxito
} else {
    http_response_code(500); // Error en el servidor
}
?>
