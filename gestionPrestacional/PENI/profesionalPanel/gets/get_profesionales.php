<?php
require_once '../../conexion/conexion.php';

// Configurar el conjunto de caracteres a UTF-8
mysqli_set_charset($conn, "utf8");

// Consulta para obtener todos los profesionales
$sql = "SELECT cod_prof, nombre, apellido FROM prof ORDER BY apellido, nombre";
$result = $conn->query($sql);

$profesionales = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profesionales[] = $row;
    }
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($profesionales);
?>
