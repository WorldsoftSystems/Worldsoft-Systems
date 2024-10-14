<?php
require_once "../../conexion.php";

// Obtener los parámetros desde la solicitud GET
$id_prof = $_GET['id_prof'] ?? '';
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los datos de los turnos
$sql = "SELECT t.*, 
               CONCAT(paci.nombre, ' - Afiliado:', paci.benef, '/', paci.parentesco, ' - ',os.siglas,' - Tel:',paci.telefono) AS nombre_paciente,
               CONCAT(a.codigo, ' - ', a.descripcion) AS motivo_full,
               p.nombreYapellido AS nom_prof
        FROM turnos t
        LEFT JOIN paciente paci ON paci.id = t.paciente
        LEFT JOIN actividades a ON a.id = t.motivo
        LEFT JOIN profesional p ON p.id_prof = t.id_prof
        LEFT JOIN obra_social os ON os.id = paci.obra_social
        WHERE t.id_prof = ? AND t.fecha BETWEEN ? AND ?
        ORDER BY t.hora ASC";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Enlazar los parámetros
$stmt->bind_param("iss", $id_prof, $fecha_desde, $fecha_hasta);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$turnos = [];
while ($row = $result->fetch_assoc()) {
    $turnos[] = $row; // Almacenar cada fila en el array
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($turnos);
?>
