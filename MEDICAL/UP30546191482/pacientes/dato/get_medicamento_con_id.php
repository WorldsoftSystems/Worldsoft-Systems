<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el id desde la solicitud GET
$id = $_GET['id'];

// Preparar la consulta para obtener los datos de la práctica específica
$sql = "SELECT mP.* , paci.nombre AS nombre_paciente
        FROM medicacion_paci mP
        LEFT JOIN paciente paci ON paci.id=mP.id_paciente
        LEFT JOIN medicacion m ON mP.medicamento=m.id
        WHERE mP.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$egreso = $result->fetch_assoc();

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($egreso);
?>
