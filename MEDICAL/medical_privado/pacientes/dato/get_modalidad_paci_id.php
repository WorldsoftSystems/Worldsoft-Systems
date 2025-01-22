<?php
require_once "../../conexion.php";

// Obtener el id_paciente desde la solicitud GET
$idPaciente = $_GET['id_paciente'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Preparar la consulta para obtener la modalidad con fecha máxima
$sql = "SELECT 
            m.id,
            m.codigo,
            m.descripcion
        FROM 
            paciente p
        LEFT JOIN 
            paci_modalidad pM ON pM.id_paciente = p.id
        LEFT JOIN 
            modalidad m ON m.id = pM.modalidad
        WHERE 
            p.id = ? 
            AND pM.fecha = (
                SELECT MIN(pM2.fecha)
                FROM paci_modalidad pM2
                WHERE pM2.id_paciente = p.id
            )";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode(['error' => 'Error en la preparación de la consulta: ' . $conn->error]));
}

$stmt->bind_param("i", $idPaciente);

if (!$stmt->execute()) {
    die(json_encode(['error' => 'Error al ejecutar la consulta: ' . $stmt->error]));
}

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($data);

$stmt->close();
$conn->close();
?>
