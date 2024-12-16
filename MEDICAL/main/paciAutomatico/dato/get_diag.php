<?php
require_once "../../conexion.php";

// Obtener el id_paciente desde la solicitud GET
$idPaciente = $_GET['id_paciente'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los egresos del paciente específico
$sql = "SELECT pD.*,d.codigo AS diag_cod, d.descripcion AS diag_desc
        FROM paci_diag pD 
        JOIN paciente p ON pD.id_paciente=p.id 
        LEFT JOIN diag d ON pD.codigo = d.id
        WHERE pD.id_paciente = $idPaciente";
$result = $conn->query($sql);

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$diagnosticos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $diagnosticos[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($diagnosticos);
?>
