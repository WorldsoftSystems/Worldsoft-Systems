<?php
// Conexión a la base de datos
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los parámetros de la URL
$profesionalId = $_GET['profesional'];
$fechaDesde = $_GET['fechaDesde'];
$fechaHasta = $_GET['fechaHasta'];

// Validar que los parámetros existan
if (!$profesionalId || !$fechaDesde || !$fechaHasta) {
    die(json_encode(["error" => "Faltan parámetros."]));
}

// Consulta para obtener los turnos filtrados por profesional y fechas
if ($profesionalId === 'all') {
    $sql = "SELECT t.fecha, t.hora, paci.nombre AS paciente, p.nombreYapellido as profesional, a.descripcion AS motivo, t.llego, t.atendido, t.observaciones, paci.telefono AS numero
            FROM turnos t
            LEFT JOIN profesional p ON t.id_prof = p.id_prof
            LEFT JOIN paciente paci ON paci.id = t.paciente
            LEFT JOIN actividades a ON a.id = t.motivo
            WHERE t.fecha BETWEEN ? AND ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fechaDesde, $fechaHasta);
} else {
    $sql = "SELECT t.fecha, t.hora, paci.nombre AS paciente, p.nombreYapellido as profesional, a.descripcion AS motivo, t.llego, t.atendido, t.observaciones, paci.telefono AS numero
            FROM turnos t
            LEFT JOIN profesional p ON t.id_prof = p.id_prof
            LEFT JOIN paciente paci ON paci.id = t.paciente
            LEFT JOIN actividades a ON a.id = t.motivo
            WHERE t.id_prof = ? AND t.fecha BETWEEN ? AND ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $profesionalId, $fechaDesde, $fechaHasta);
}

$stmt->execute();
$resultado = $stmt->get_result();

// Inicializar el array para almacenar los turnos
$turnos = [];

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // Formatear la fecha en dd/mm/aaaa
        $fechaFormateada = date("d/m/Y", strtotime($row['fecha']));
        // Formatear la hora en HH:mm (sin segundos)
        $horaFormateada = date("H:i", strtotime($row['hora']));
        
        // Agregar el turno al array
        $turnos[] = [
            'fecha' => $fechaFormateada,
            'hora' => $horaFormateada,
            'paciente' => $row['paciente'],
            'profesional' => $row['profesional'],
            'motivo' => $row['motivo'],
            'llego' => $row['llego'],
            'atendido' => $row['atendido'],
            'observaciones' => $row['observaciones'],
            'numero' => $row['numero'],
        ];
    }
}

// Cerrar la conexión
$conn->close();

// Configurar la respuesta JSON
header('Content-Type: application/json');
echo json_encode($turnos);
?>
