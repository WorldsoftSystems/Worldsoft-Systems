<?php
require_once "../../conexion.php";

// Obtener el id desde la solicitud GET
$id = $_GET['id'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los datos de la práctica específica
$sql = "SELECT *, prof.nombreYapellido AS profesional_full, a.descripcion AS actividad_full
        FROM paciente p
        LEFT JOIN pagos pa ON p.id = pa.id_paciente
        LEFT JOIN profesional prof ON prof.id_prof = pa.id_profesional
        LEFT JOIN actividades a ON a.id = pa.actividad
        WHERE pa.id_pago = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$diag = $result->fetch_assoc();

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($diag);
?>
