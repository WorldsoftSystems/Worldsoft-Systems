<?php
include('../../conexion.php');

$id = $_GET['id'];

$result = $conn->query("SELECT v.*
                        FROM vencimientos v
                        WHERE v.id = $id");
$movimiento = $result->fetch_assoc();

echo json_encode($movimiento);
?>