<?php
require_once "../../conexion.php";

$id_prof = $_GET['id_prof'] ?? '';
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL: incluir filtro para `id_prof` solo si no está vacío
$sql = "SELECT t.*, 
               CONCAT(paci.nombre, ' - Afiliado:', paci.benef, '/', paci.parentesco, ' - ', os.siglas, ' - Tel:', COALESCE(paci.telefono, 'Sin teléfono')) AS nombre_paciente,
               CONCAT(a.codigo, ' - ', a.descripcion) AS motivo_full,
               p.nombreYapellido AS nom_prof
        FROM turnos t
        LEFT JOIN paciente paci ON paci.id = t.paciente
        LEFT JOIN actividades a ON a.id = t.motivo
        LEFT JOIN profesional p ON p.id_prof = t.id_prof
        LEFT JOIN obra_social os ON os.id = paci.obra_social
        WHERE t.fecha BETWEEN ? AND ?";

// Agregar filtro de profesional solo si `id_prof` no está vacío
if ($id_prof !== '') {
    $sql .= " AND t.id_prof = ?";
}
$sql .= " ORDER BY t.id_prof, t.hora ASC";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Vincular parámetros de acuerdo a si `id_prof` está vacío o no
if ($id_prof !== '') {
    $stmt->bind_param("ssi", $fecha_desde, $fecha_hasta, $id_prof);
} else {
    $stmt->bind_param("ss", $fecha_desde, $fecha_hasta);
}

$stmt->execute();
$result = $stmt->get_result();

$turnos = [];
while ($row = $result->fetch_assoc()) {
    $turnos[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($turnos);
?>
