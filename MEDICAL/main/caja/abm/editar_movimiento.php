<?php
include ('../../conexion.php');

$id = $_POST['id'];
$fecha = $_POST['fecha'];
$detalle = $_POST['detalle'];
$ingreso = $_POST['ingreso'] ?: null;
$egreso = $_POST['egreso'] ?: null;

$stmt = $conn->prepare("UPDATE movimientos SET fecha = ?, detalle = ?, ingreso = ?, egreso = ? WHERE id = ?");
$stmt->bind_param("ssddi", $fecha, $detalle, $ingreso, $egreso, $id);
$stmt->execute();
?>
