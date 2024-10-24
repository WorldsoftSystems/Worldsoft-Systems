<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el término de búsqueda de la consulta GET
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Escapar el término de búsqueda para prevenir inyecciones SQL
$searchQuery = $conn->real_escape_string($searchQuery);

// Ejecutar la consulta para obtener todos los pacientes que coinciden con el término de búsqueda
$sql = "SELECT * FROM paciente
        WHERE nombre LIKE '%$searchQuery%' 
        OR benef LIKE '%$searchQuery%' 
        OR parentesco LIKE '%$searchQuery%'
        ORDER BY nombre ASC";

$result = $conn->query($sql);

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$pacientes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($pacientes);
?>
