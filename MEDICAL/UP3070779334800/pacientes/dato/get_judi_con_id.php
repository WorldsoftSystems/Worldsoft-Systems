<?php
require_once "../../conexion.php";

// Obtener el id desde la solicitud GET
$id = $_GET['id'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los datos de la práctica específica
$sql = "SELECT j.* , paci.nombre AS nombre_paciente
        FROM judiciales j
        LEFT JOIN paciente paci ON paci.id=j.id_paciente
        WHERE j.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$judiciales = $result->fetch_assoc();

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($judiciales);
?>
