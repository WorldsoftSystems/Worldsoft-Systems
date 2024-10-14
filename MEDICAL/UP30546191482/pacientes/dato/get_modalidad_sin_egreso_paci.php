<?php
require_once "../../conexion.php";

// Obtener el id_paciente desde la solicitud GET
$idPaciente = $_GET['id_paciente'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener las modalidades que no han sido egresadas para el paciente específico
$sql = "SELECT pM.*, CONCAT(m.codigo, ' - ', m.descripcion) AS modalidad_full
    FROM paci_modalidad pM
    JOIN modalidad m ON pM.modalidad = m.id
    LEFT JOIN egresos e ON pM.modalidad = e.modalidad AND pM.id_paciente = e.id_paciente
    WHERE pM.id_paciente = ? AND e.id_paciente IS NULL
    ORDER BY pM.fecha DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idPaciente);
$stmt->execute();
$result = $stmt->get_result();

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$modalidades = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $modalidades[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($modalidades);
?>
