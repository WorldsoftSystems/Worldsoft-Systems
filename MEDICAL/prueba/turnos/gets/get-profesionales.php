<?php
// Incluir el archivo de conexión
include('../../conexion.php');

$sql = "SELECT p.id_prof, p.nombreYapellido, CONCAT(p.nombreYapellido, ' - ', e.desc_especialidad) AS prof_full
        FROM profesional p
        LEFT JOIN especialidad e ON p.id_especialidad = e.id_especialidad";
$result = $conn->query($sql);

$profesionales = array();

while($row = $result->fetch_assoc()) {
    $profesionales[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($profesionales);
?>
