<?php
require_once "../../conexion.php";

// Obtener el id desde la solicitud GET
$id = $_GET['id'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los datos de la práctica específica
$sql = "SELECT e.* , prof.nombreYapellido AS profesional, paci.nombre AS nombre_paciente ,CONCAT(d.codigo, '-', d.descripcion) AS ult_diag
        FROM evoluciones_amb e
        LEFT JOIN paciente paci ON paci.id=e.id_paciente
        LEFT JOIN profesional prof ON prof.id_prof = e.id_prof
        LEFT JOIN paci_diag pD ON pD.id_paciente = paci.id
        LEFT JOIN diag d ON d.id = pD.codigo
        WHERE e.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$evoluciones = $result->fetch_assoc();

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($evoluciones);
?>
