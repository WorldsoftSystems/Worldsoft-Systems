<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ejecutar la consulta
$sql = "SELECT id, codigo,descripcion FROM tipo_egreso";
$result = $conn->query($sql);

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$tipo_egreso = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tipo_egreso[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($tipo_egreso);
?>