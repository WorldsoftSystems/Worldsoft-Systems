<?php
require_once "../../conexion.php";

// Obtener el id_paciente desde la solicitud GET
$idPaciente = $_GET['id_paciente'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener la fecha máxima de paci_modalidad
$sqlFechaMaxima = "
    SELECT MAX(pM.fecha) AS fecha_maxima
    FROM paci_modalidad pM
    WHERE pM.id_paciente = ?
";

$stmtFechaMaxima = $conn->prepare($sqlFechaMaxima);
$stmtFechaMaxima->bind_param("i", $idPaciente);
$stmtFechaMaxima->execute();
$resultFechaMaxima = $stmtFechaMaxima->get_result();
$rowFechaMaxima = $resultFechaMaxima->fetch_assoc();
$fechaMaxima = $rowFechaMaxima['fecha_maxima'];

// Preparar la consulta para contar los egresos que cumplen la condición
$sqlEgresos = "
    SELECT COUNT(*) AS egresado
    FROM egresos e
    WHERE e.id_paciente = ? AND e.fecha_egreso IS NOT NULL AND e.fecha_egreso >= ?
";

$stmtEgresos = $conn->prepare($sqlEgresos);
$stmtEgresos->bind_param("is", $idPaciente, $fechaMaxima);
$stmtEgresos->execute();
$resultEgresos = $stmtEgresos->get_result();
$rowEgresos = $resultEgresos->fetch_assoc();
$isEgresado = $rowEgresos['egresado'] > 0;

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver el resultado como JSON
echo json_encode(['egresado' => $isEgresado]);
?>
