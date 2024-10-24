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
$sql = "SELECT paci.telefono AS tel,
               paci.nombre as nombre_paci,
               p.nombreYapellido AS nom_prof,
               t.fecha as fecha_turno,
               t.hora AS hora_turno,
               ps.dir AS direccion_inst,
               ps.tel AS tel_inst,
               ps.inst AS nombre_inst
        FROM turnos t
        LEFT JOIN paciente paci ON paci.id = t.paciente
        LEFT JOIN profesional p ON p.id_prof = t.id_prof
        LEFT JOIN parametro_sistema ps ON 1 = 1 -- Join sin condición para obtener datos de una sola fila
        WHERE t.id_prof = ? 
        AND t.fecha BETWEEN ? AND ?
        ";

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
$nums = [];
while ($row = $result->fetch_assoc()) {
    $nums[] = $row; // Almacenar cada fila en el array
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($nums);
?>
