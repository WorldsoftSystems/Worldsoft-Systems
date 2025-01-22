<?php
require_once "../../../conexion.php";

header('Content-Type: application/json');

$sql = "SELECT * FROM bocas_atencion";
$result = $conn->query($sql);

$bocas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bocas[] = $row;
    }
}

echo json_encode($bocas);
$conn->close();
?>
