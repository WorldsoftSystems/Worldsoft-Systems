<?php
include('../../conexion.php');

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

    // Crear los intervalos de tiempo
    $intervalos = array();
    while ($hora_inicio < $hora_fin) {
        $intervalos[] = $hora_inicio->format('H:i');
        $hora_inicio->add(new DateInterval("PT{$intervalo}M"));
    }

    // Verificar si ya existe una disponibilidad para ese día
    $found = false;
    foreach ($disponibilidad as &$disp) {
        if ($disp['dia_semana'] === $dia_semana) {
            // Fusionar los intervalos si ya existe una disponibilidad para ese día
            $disp['intervalos'] = array_merge($disp['intervalos'], $intervalos);
            $found = true;
            break;
        }
    }

    // Si no existe una disponibilidad para el día, agregar una nueva
    if (!$found) {
        $disponibilidad[] = array(
            "dia_semana" => $dia_semana,
            "intervalos" => $intervalos
        );
    }
}

$stmt_disponibilidad->close();

// Verificar si la columna 'token' existe en la tabla 'paciente'
$col_check = $conn->query("SHOW COLUMNS FROM paciente LIKE 'token'");
$has_token = $col_check && $col_check->num_rows > 0;

// Definir el fragmento del SELECT según corresponda
$select_token = $has_token ? "paci.token," : "'' AS token,";

// Obtener turnos del día para el profesional seleccionado, incluyendo el nombre del paciente
$turnos_sql = "SELECT t.*, CONCAT(paci.nombre, ' - ', paci.benef, '/', paci.parentesco) AS nombre_paciente,
                      paci.id AS paciente_id , CONCAT(a.codigo, ' - ', a.descripcion) AS motivo_full,
                      paci.telefono,CONCAT('Amb: ', paci.nro_hist_amb, ' / Int: ', paci.nro_hist_int) AS nro_hc,
                      $select_token
                      1 AS dummy_check -- evita coma final flotante
               FROM turnos t
               LEFT JOIN paciente paci ON paci.id = t.paciente
               LEFT JOIN actividades a ON a.id = t.motivo
               WHERE t.fecha = ? AND t.id_prof = ?
               ORDER BY t.hora ASC"; // Ordenar por hora

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

// Obtener ausencias del profesional
$ausencias_sql = "SELECT * FROM ausencias WHERE id_prof = ?";
$stmt_ausencias = $conn->prepare($ausencias_sql);
$stmt_ausencias->bind_param("i", $prof);
$stmt_ausencias->execute();
$result_ausencias = $stmt_ausencias->get_result();

$ausencias = array();
while ($row = $result_ausencias->fetch_assoc()) {
    $ausencias[] = $row;
}
$stmt_ausencias->close();


$conn->close();

// Preparar respuesta JSON
$response = array(
    "disponibilidad" => $disponibilidad,
    "turnos" => $turnos,
    "todos_turnos" => $turnos_todos,
    "ausencias" => $ausencias
);

header('Content-Type: application/json');
echo json_encode($response);
?>