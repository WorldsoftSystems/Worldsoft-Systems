<?php
// Incluir el archivo de conexión
include('../../conexion.php');

$sql = "SELECT *
        FROM parametro_sistema";
$result = $conn->query($sql);

$parametros = array();

while($row = $result->fetch_assoc()) {
    $parametros[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($parametros);
?>
