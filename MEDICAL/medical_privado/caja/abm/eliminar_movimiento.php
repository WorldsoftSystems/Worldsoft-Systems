<?php
include ('../../conexion.php');

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM movimientos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
?>
