<?php
require_once "../../conexion.php";

// Obtener el id desde la solicitud GET
$id = $_GET['id_paciente_turno'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los datos de la práctica específica
$sql = "SELECT MAX(t.fecha) AS ultima_fecha, t.hora , p.nombreYapellido as nom_prof
        FROM turnos t
        LEFT JOIN profesional p ON p.id_prof = t.id_prof
        WHERE t.paciente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$turno = $result->fetch_assoc();

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($turno);
?>
