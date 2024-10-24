<?php
include ('../../conexion.php');

// Obtener parámetros
if (!isset($_GET['date']) || !isset($_GET['prof'])) {
    $error_response = array(
        "error" => "Parámetros 'date' y 'prof' son requeridos."
    );
    header('Content-Type: application/json');
    echo json_encode($error_response);
    exit;
}

$date = $_GET['date'];
$prof = $_GET['prof'];

// Obtener disponibilidad (hora_inicio, hora_fin, dia_semana, intervalo)
$disponibilidad_sql = "SELECT hora_inicio, hora_fin, dia_semana, intervalo FROM disponibilidad WHERE id_prof = ?";
$stmt_disponibilidad = $conn->prepare($disponibilidad_sql);
$stmt_disponibilidad->bind_param("i", $prof);
$stmt_disponibilidad->execute();

$disponibilidad_result = $stmt_disponibilidad->get_result();

$disponibilidad = array();
while ($row = $disponibilidad_result->fetch_assoc()) {
    $dia_semana = strtolower($row['dia_semana']);
    $hora_inicio = new DateTime($row['hora_inicio']);
    $hora_fin = new DateTime($row['hora_fin']);
    $intervalo = $row['intervalo'];

    $intervalos = array();
    while ($hora_inicio < $hora_fin) {
        $intervalos[] = $hora_inicio->format('H:i');
        $hora_inicio->add(new DateInterval("PT{$intervalo}M"));
    }

    $disponibilidad[] = array(
        "dia_semana" => $dia_semana,
        "intervalos" => $intervalos
    );
}

$stmt_disponibilidad->close();

// Obtener turnos del día para el profesional seleccionado, incluyendo el nombre del paciente
$turnos_sql = "SELECT t.*, CONCAT(paci.nombre, ' - ', paci.benef, '/', paci.parentesco) AS nombre_paciente, paci.id AS paciente_id , CONCAT(a.codigo, ' - ', a.descripcion) AS motivo_full
               FROM turnos t
               LEFT JOIN paciente paci ON paci.id = t.paciente
               LEFT JOIN actividades a ON a.id = t.motivo
               WHERE t.fecha = ? AND t.id_prof = ?";
$stmt_turnos = $conn->prepare($turnos_sql);
$stmt_turnos->bind_param("si", $date, $prof);
$stmt_turnos->execute();

$result_turnos = $stmt_turnos->get_result();

$turnos = array();
while ($row = $result_turnos->fetch_assoc()) {
    $turnos[] = $row;
}

$stmt_turnos->close();


// Obtener todos los turnos desde la fecha especificada
$turnos_sql_todos = "SELECT *
                     FROM turnos
                     WHERE fecha >= ? AND id_prof = ?";
$stmt_turnos_todos = $conn->prepare($turnos_sql_todos);
$stmt_turnos_todos->bind_param("si", $date, $prof);
$stmt_turnos_todos->execute();

$result_turnos_todos = $stmt_turnos_todos->get_result();

$turnos_todos = array();
while ($row = $result_turnos_todos->fetch_assoc()) {
    $turnos_todos[] = $row;
}

$stmt_turnos_todos->close();
$conn->close();

// Preparar respuesta JSON
$response = array(
    "disponibilidad" => $disponibilidad,
    "turnos" => $turnos,
    "todos_turnos" => $turnos_todos
);

header('Content-Type: application/json');
echo json_encode($response);
?>