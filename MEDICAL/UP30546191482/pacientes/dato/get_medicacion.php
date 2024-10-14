<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los parámetros de paginación y búsqueda
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 100;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Calcular el OFFSET para la paginación
$offset = ($page - 1) * $per_page;

// Ejecutar la consulta con paginación y búsqueda
$sql = "SELECT id, descripcion FROM medicacion WHERE descripcion LIKE '%$search%' ORDER BY descripcion ASC LIMIT $per_page OFFSET $offset";
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
