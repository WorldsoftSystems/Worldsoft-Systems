<?php
require_once "../../conexion.php";

// Obtener el id desde la solicitud GET
$id = $_GET['id'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los datos de la práctica específica
$sql = "SELECT h.*,TIMESTAMPDIFF(YEAR, p.fecha_nac, CURDATE()) AS edad,p.nombre AS nombre_paciente, prof.nombreYapellido AS prof_full
        FROM hc_admision_ambulatorio h
        LEFT JOIN paciente p ON h.id_paciente = p.id
        LEFT JOIN profesional prof ON prof.id_prof = h.id_prof
        
        WHERE h.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$salidas = $result->fetch_assoc();

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($salidas);
?>
