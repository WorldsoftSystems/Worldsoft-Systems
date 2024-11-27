<?php
require_once "../../conexion.php";

// Obtener el id_paciente desde la solicitud GET
$idPaciente = $_GET['id_paciente'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los egresos del paciente específico
$sql = "SELECT o.*, m.descripcion AS modalidad_full
        FROM paci_op o
        LEFT JOIN paciente p ON o.id_paciente = p.id
        LEFT JOIN modalidad m ON m.id = o.modalidad_op
        WHERE o.id_paciente = $idPaciente";
$result = $conn->query($sql);


// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$ordenes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ordenes[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($ordenes);
?>
