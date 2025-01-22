<?php
ini_set('display_errors', 0);  // Desactiva la visualización de errores
error_reporting(0);  // Desactiva la generación de reportes de errores

require_once "../../conexion.php";

// Obtener el id desde la solicitud GET
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Preparar la consulta para obtener los datos del paciente
$sql = "SELECT p.*,o.siglas AS obra,TIMESTAMPDIFF(YEAR, p.fecha_nac, CURDATE()) AS edad
        FROM paciente p
        LEFT JOIN obra_social o ON o.id=p.obra_social
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Error en la preparación de la consulta']);
    exit;
}

$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$paciente = $result->fetch_assoc();

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($paciente);

?>
