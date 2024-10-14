<?php
include ('../../conexion.php');

$result = $conn->query("SELECT m.*, CONCAT(r.descripcion, ' - ', c.desc_cuenta) AS detalle_full
                        FROM movimientos m
                        LEFT JOIN cuentas c ON c.id = m.detalle
                        LEFT JOIN rubros r ON c.desc_rubro = r.id
                        ORDER BY fecha DESC");
$movimientos = [];

while ($row = $result->fetch_assoc()) {
    $movimientos[] = $row;
}

echo json_encode($movimientos);
?>
