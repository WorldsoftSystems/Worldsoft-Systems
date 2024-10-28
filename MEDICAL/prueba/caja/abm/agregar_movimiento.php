<?php
include ('../../conexion.php');

$fecha = $_POST['fecha'];
$detalle = $_POST['detalle'];
$ingreso = $_POST['ingreso'] ?: null;
$egreso = $_POST['egreso'] ?: null;

$stmt = $conn->prepare("INSERT INTO movimientos (fecha, detalle, ingreso, egreso) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sidd", $fecha, $detalle, $ingreso, $egreso);
$stmt->execute();
?>
