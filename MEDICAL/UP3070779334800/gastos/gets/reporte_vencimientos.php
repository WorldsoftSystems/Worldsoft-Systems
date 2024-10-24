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

$query = "SELECT v.*, CONCAT(r.descripcion, ' - ', c.desc_cuenta) AS detalle_full, p.descripcion AS periodo_full, compro.descripcion AS comprobante_full
    FROM vencimientos v
    LEFT JOIN cuentas c ON c.id = v.detalle
    LEFT JOIN rubros r ON c.desc_rubro =  r.id
    LEFT JOIN periodo p ON p.id = v.periodo
    LEFT JOIN comprobantes compro ON compro.id=v.comprobante
    WHERE v.fecha_vencimiento BETWEEN ? AND ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $fechaDesde, $fechaHasta);
$stmt->execute();
$result = $stmt->get_result();
$vencimientos = $result->fetch_all(MYSQLI_ASSOC);

// Log de resultados
error_log("Datos obtenidos: " . print_r($vencimientos, true));

echo json_encode($vencimientos);
?>