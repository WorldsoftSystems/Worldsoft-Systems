<?php
// Incluir el archivo de conexión
include ('../../conexion.php');

// Consulta
$sql = "SELECT 
            p.nombre AS nombre_paciente,
            p.admision AS fecha_admision,
            e.fecha_egreso AS fecha_egreso,
            DATEDIFF(e.fecha_egreso, p.admision) AS dias_estadia
        FROM 
            paciente p
        INNER JOIN 
            egresos e 
        ON 
            p.id = e.id_paciente
        WHERE 
            e.fecha_egreso IS NOT NULL";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Enviar datos como JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
