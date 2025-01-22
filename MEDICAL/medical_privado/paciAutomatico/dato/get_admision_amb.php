<?php
// Configuración para desactivar la visualización y reporte de errores


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
$sql = "SELECT o.siglas AS obra, TIMESTAMPDIFF(YEAR, p.fecha_nac, CURDATE()) AS edad, 
               r.nombreYapellido AS responsable, prof.nombreYapellido AS profesional, h.*
        FROM hc_admision_ambulatorio h
        LEFT JOIN paciente p ON h.id_paciente = p.id
        LEFT JOIN obra_social o ON o.id = p.obra_social
        LEFT JOIN profesional prof ON prof.id_prof = h.id_prof
        LEFT JOIN responsable r ON r.id_paciente = p.id
        WHERE p.id = ?
        GROUP BY h.id
        ORDER BY h.hc_fecha DESC";
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
$historia = [];
while ($row = $result->fetch_assoc()) {
    $historia[] = $row;  // Agregar cada fila al array $historia
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($historia);
?>
