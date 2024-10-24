<?php
header('Content-Type: application/json');

include('../../conexion.php');

$fechaDesde = $_GET['fechaDesde'] ?? '';
$fechaHasta = $_GET['fechaHasta'] ?? '';

// Log de fechas
error_log("Fecha Desde: " . $fechaDesde);
error_log("Fecha Hasta: " . $fechaHasta);

// Verifica que las fechas sean válidas
if (empty($fechaDesde) || empty($fechaHasta)) {
    echo json_encode([]);
    exit();
}

$query = "SELECT m.fecha, c.desc_rubro, m.detalle, m.ingreso, m.egreso , r.descripcion
    FROM movimientos m
    JOIN cuentas c ON m.detalle = c.id
    LEFT JOIN rubros r ON c.desc_rubro = r.id
    WHERE m.fecha BETWEEN ? AND ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $fechaDesde, $fechaHasta);
$stmt->execute();
$result = $stmt->get_result();
$movimientos = $result->fetch_all(MYSQLI_ASSOC);

// Log de resultados
error_log("Datos obtenidos: " . print_r($movimientos, true));

echo json_encode($movimientos);
?>
