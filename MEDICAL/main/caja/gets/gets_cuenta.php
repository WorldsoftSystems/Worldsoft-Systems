<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ejecutar la consulta
$sql = "SELECT c.*,r.descripcion FROM cuentas c LEFT JOIN rubros r ON c.desc_rubro = r.id";
$result = $conn->query($sql);

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$cuentas = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cuentas[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($cuentas);
?>
