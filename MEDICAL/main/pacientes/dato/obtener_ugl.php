<?php
require_once "../../conexion.php";

$sql = "SELECT * FROM codigo_ugl"; // Ajusta la consulta según tus necesidades
$result = $conn->query($sql);

$ugl = array();
while ($row = $result->fetch_assoc()) {
    $ugl[] = $row;
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($ugl);
?>
