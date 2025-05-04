<?php
include('../../conexion.php');

$sql = "SELECT id, usuario, clave FROM usuarios";
$result = $conn->query($sql);

$usuarios = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($usuarios);
