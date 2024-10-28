<?php
include ('../../conexion.php');

$fecha = $_POST['fecha'];
$detalle = $_POST['detalle'];
$importe = $_POST['importe'] ?: null;
$periodo = $_POST['periodo'] ?: null;
$comprobante = $_POST['comprobante'] ?: null;
$num_comprobante = $_POST['num_comprobante'] ?: null;

$stmt = $conn->prepare("INSERT INTO vencimientos (fecha_vencimiento, detalle, periodo, comprobante, importe, num_comprobante) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("siiiii", $fecha, $detalle, $periodo, $comprobante, $importe, $num_comprobante);
$stmt->execute();
?>
