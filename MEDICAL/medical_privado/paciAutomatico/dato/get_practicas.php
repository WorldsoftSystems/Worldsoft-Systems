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
$sql = "SELECT pR.*, CONCAT(prof.nombreYapellido, ' - ', esp.desc_especialidad) AS prof_full,
               CONCAT(act.codigo, ' - ', act.descripcion) AS act_full
        FROM practicas pR
        JOIN paciente p ON pR.id_paciente=p.id 
        LEFT JOIN profesional prof ON pR.profesional = prof.id_prof
        LEFT JOIN especialidad esp ON prof.id_especialidad = esp.id_especialidad
        LEFT JOIN actividades act ON pR.actividad = act.id
        WHERE pR.id_paciente = ?
        ORDER BY pR.fecha DESC
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
$practicas = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $practicas[] = $row;
    }
}

// Consulta para obtener el total de registros
$totalQuery = "SELECT COUNT(*) as total FROM practicas WHERE id_paciente = ?";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param("i", $idPaciente);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRecords = $totalResult->fetch_assoc()['total'];

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode([
    'practicas' => $practicas,
    'totalRecords' => $totalRecords
]);
?>
