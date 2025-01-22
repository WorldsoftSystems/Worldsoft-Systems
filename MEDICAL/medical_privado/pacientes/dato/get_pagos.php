<?php
require_once "../../conexion.php";

// Obtener parámetros desde la solicitud GET
$idPaciente = $_GET['id_paciente'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : 10;
$offset = ($page - 1) * $recordsPerPage;

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los egresos del paciente específico con LIMIT y OFFSET
$sql = "SELECT *, prof.nombreYapellido AS profesional_full, a.descripcion AS actividad_full,SUM(pa.monto_total - pa.monto_pago) AS deuda
        FROM paciente p
        LEFT JOIN pagos pa ON p.id = pa.id_paciente
        LEFT JOIN profesional prof ON prof.id_prof = pa.id_profesional
        LEFT JOIN actividades a ON a.id = pa.actividad
        WHERE p.id = ?
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $idPaciente, $recordsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$pagos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }
}

// Consulta para obtener el total de registros
$totalQuery = "SELECT COUNT(*) as total FROM pagos WHERE id_paciente = ?";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param("i", $idPaciente);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRecords = $totalResult->fetch_assoc()['total'];

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode([
    'pagos' => $pagos,
    'totalRecords' => $totalRecords
]);
?>
