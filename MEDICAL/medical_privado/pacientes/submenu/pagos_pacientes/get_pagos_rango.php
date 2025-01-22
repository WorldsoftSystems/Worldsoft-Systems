<?php
require_once "../../../conexion.php";

error_reporting(E_ALL);  // Habilitar todos los errores
ini_set('display_errors', 1);  // Mostrar errores de PHP

header('Content-Type: application/json');

// Obtener los parámetros directamente de la URL
$idPaciente = $_GET['idPaciente'] ?? null;
$fechaDesde = $_GET['fechaDesde'] ?? null;
$fechaHasta = $_GET['fechaHasta'] ?? null;

// Verificar si los parámetros se recibieron correctamente
$missingParams = [];
if (!$idPaciente) {
    $missingParams[] = 'idPaciente';
}
if (!$fechaDesde) {
    $missingParams[] = 'fechaDesde';
}
if (!$fechaHasta) {
    $missingParams[] = 'fechaHasta';
}

if (count($missingParams) > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos necesarios.',
        'missingParams' => $missingParams
    ]);
    exit;
}

// Consultar los pagos en el rango de fechas
$sql = "SELECT 
            pg.fecha_pago, 
            pr.nombreYapellido AS nombre_profesional, 
            pg.actividad, 
            pg.monto_pago, 
            pg.monto_total, 
            pg.metodo_pago,
            p.nombre AS nombre_paciente,
            a.descripcion AS actividad_full
        FROM pagos pg
        LEFT JOIN paciente p ON pg.id_paciente = p.id
        LEFT JOIN actividades a ON pg.actividad = a.id
        INNER JOIN profesional pr ON pg.id_profesional = pr.id_prof
        WHERE pg.id_paciente = ? AND pg.fecha_pago BETWEEN ? AND ?";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta.', 'error' => $conn->error]);
    exit;
}

$stmt->bind_param("iss", $idPaciente, $fechaDesde, $fechaHasta);

// Ejecutar la consulta y verificar que no haya errores
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta.', 'error' => $stmt->error]);
    exit;
}

// Obtener los resultados
$result = $stmt->get_result();

// Verificar si se encontraron pagos
if ($result->num_rows > 0) {
    $pagos = [];
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }
    // Devolver los pagos en formato JSON
    echo json_encode(['status' => 'success', 'pagos' => $pagos]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se encontraron pagos en el rango de fechas.']);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
