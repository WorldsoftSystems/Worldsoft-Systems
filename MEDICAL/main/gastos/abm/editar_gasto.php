<?php
include ('../../conexion.php');

$id = $_POST['id'];
$fecha = $_POST['fecha'];
$detalle = $_POST['detalle'];
$importe = $_POST['importe'] ?: null;
$periodo = $_POST['periodo'] ?: null;
$comprobante = $_POST['comprobante'] ?: null;
$num_comprobante = $_POST['num_comprobante'] ?: null;

$stmt = $conn->prepare("UPDATE vencimientos SET 	fecha_vencimiento = ?, detalle = ?, periodo = ?, comprobante = ?, importe = ?, num_comprobante = ? WHERE id = ?");
$stmt->bind_param("siiiiii", $fecha, $detalle, $periodo, $comprobante, $importe,$num_comprobante, $id);
$stmt->execute();
?>
