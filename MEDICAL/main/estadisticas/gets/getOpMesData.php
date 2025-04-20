<?php
require_once "../../conexion.php";

$fechaDesde = $_GET['desde'];
$fechaHasta = $_GET['hasta'];

// Consulta SQL con JOIN entre paciente y paci_op
$sql = "SELECT 
            p.nombre,
            po.fecha,
            po.op,
            po.cant,
            po.modalidad_op,
            po.fecha_vencimiento,
            m.descripcion AS modalidad_desc
        FROM paci_op po
        JOIN paciente p ON p.id = po.id_paciente
        JOIN modalidad m ON m.id = po.modalidad_op
        WHERE (po.fecha_vencimiento BETWEEN ? AND ?) OR (po.fecha_vencimiento > ?)
        ORDER BY po.modalidad_op, po.fecha";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $fechaDesde, $fechaDesde, $fechaHasta);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $mod = $row['modalidad_op'];
    if (!isset($data[$mod])) {
        $data[$mod] = [];
    }
    $data[$mod][] = $row;
}

echo json_encode($data);
?>
