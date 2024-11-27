<?php
include ('../../conexion.php');

$result = $conn->query("SELECT v.*, CONCAT(r.descripcion, ' - ', c.desc_cuenta) AS detalle_full, p.descripcion AS periodo, compro.descripcion AS comprobante
                        FROM vencimientos v
                        LEFT JOIN cuentas c ON c.id = v.detalle
                        LEFT JOIN rubros r ON c.desc_rubro =  r.id
                        LEFT JOIN periodo p ON p.id = v.periodo
                        LEFT JOIN comprobantes compro ON compro.id=v.comprobante
                        ORDER BY fecha_vencimiento DESC");
$vencimientos = [];

while ($row = $result->fetch_assoc()) {
    $vencimientos[] = $row;
}

echo json_encode($vencimientos);
?>
