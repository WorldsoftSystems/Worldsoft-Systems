<?php
require_once "../../conexion.php";

// Obtener el id_paciente y las fechas desde la solicitud GET
$idPaciente = $_GET['id_paciente'];
$fechaDesde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : null; // Fecha desde
$fechaHasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : null; // Fecha hasta

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta base
$sql = "SELECT mP.*, CONCAT(m.descripcion, ' - ', m.potencia) AS desc_medi, p.nombre AS nombre_paciente
        FROM medicacion_paci mP
        JOIN paciente p ON mP.id_paciente=p.id 
        LEFT JOIN medicacion m ON mP.medicamento = m.id
        WHERE mP.id_paciente = $idPaciente";

// Agregar condiciones de fecha a la consulta si están definidas
if ($fechaDesde && $fechaHasta) {
    $sql .= " AND mP.fecha BETWEEN '$fechaDesde' AND '$fechaHasta'";
}

// Ejecutar la consulta
$result = $conn->query($sql);

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$medicaciones = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $medicaciones[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($medicaciones);
?>
