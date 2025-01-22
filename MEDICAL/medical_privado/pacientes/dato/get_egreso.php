<?php
require_once "../../conexion.php";

// Obtener el id_paciente desde la solicitud GET
$idPaciente = $_GET['id_paciente'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los egresos del paciente específico
$sql = "SELECT e.*, 
        IFNULL(CONCAT(d.codigo, ' - ', d.descripcion), '') AS diag_full, 
        IFNULL(CONCAT(m.codigo, ' - ', m.descripcion), '') AS modalidad_full, 
        IFNULL(CONCAT(t.codigo, ' - ', t.descripcion), '') AS egreso_full
        FROM egresos e 
        JOIN paciente p ON e.id_paciente = p.id 
        LEFT JOIN diag d ON e.diag = d.id 
        LEFT JOIN modalidad m ON e.modalidad = m.id 
        LEFT JOIN tipo_egreso t ON e.motivo = t.id
        WHERE e.id_paciente = $idPaciente";

$result = $conn->query($sql);


// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$egresos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $egresos[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($egresos);
?>
