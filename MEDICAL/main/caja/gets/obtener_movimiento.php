<?php
include('../../conexion.php');

$id = $_GET['id'];

$result = $conn->query("SELECT m.*, CONCAT(r.descripcion, ' - ', c.desc_cuenta) AS detalle_full
                        FROM movimientos m
                        LEFT JOIN cuentas c ON c.id = m.detalle
                        LEFT JOIN rubros r ON c.desc_rubro = r.id
                        WHERE m.id = $id");
$movimiento = $result->fetch_assoc();

echo json_encode($movimiento);
?>