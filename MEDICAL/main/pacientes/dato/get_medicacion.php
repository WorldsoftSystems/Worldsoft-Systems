<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el parámetro de búsqueda
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Definir la consulta
$sql = "SELECT id, descripcion, potencia FROM medicacion WHERE descripcion LIKE '%$search%' ORDER BY descripcion ASC";

// Ejecutar la consulta
$result = $conn->query($sql);

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$medicacion = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $medicacion[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($medicacion);
?>
