<?php
require_once "../../../conexion.php";

// Función para formatear fechas en formato DD/MM/YYYY
function formatDateToArg($date) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    return $dateObj ? $dateObj->format('d/m/Y') : $date;
}

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$hora = $_POST['hora'];
$profesional = $_POST['profesional'];
$actividad = $_POST['actividad'];
$cant = $_POST['cant'];
$fechas = json_decode($_POST['fechas'], true); // Decodificar el array JSON de fechas

// Obtener la fecha de admisión del paciente
$sqlAdmision = "SELECT admision FROM paciente WHERE id = ?";
$stmtAdmision = $conn->prepare($sqlAdmision);
$stmtAdmision->bind_param('i', $idPaciente);
$stmtAdmision->execute();
$stmtAdmision->bind_result($fechaAdmision);
$stmtAdmision->fetch();
$stmtAdmision->close();

// Formatear la fecha de admisión al formato argentino
$fechaAdmisionArg = formatDateToArg($fechaAdmision);

$error = false; // Variable para controlar si hay un error

foreach ($fechas as $fecha) {
    if ($fecha < $fechaAdmision) {
        $error = true;
        // Formatear las fechas al formato argentino
        $fechaPracticaArg = formatDateToArg($fecha);
        $response = array(
            'status' => 'error',
            'message' => "La fecha de práctica ($fechaPracticaArg) no puede ser anterior a la fecha de admisión ($fechaAdmisionArg)."
        );
        echo json_encode($response);
        exit; // Salir del script si hay un error
    }
}

if (!$error) {
    // Insertar prácticas si no hubo errores
    $sql = "INSERT INTO practicas (id_paciente, fecha, hora, profesional, actividad, cant)
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    foreach ($fechas as $fecha) {
        $stmt->bind_param('issssi', $idPaciente, $fecha, $hora, $profesional, $actividad, $cant);
        if (!$stmt->execute()) {
            $response = array(
                'status' => 'error',
                'message' => "Error al insertar práctica: " . $stmt->error
            );
            echo json_encode($response);
            exit; // Salir del script si hay un error al insertar
        }
    }

    $stmt->close();
    $response = array(
        'status' => 'success',
        'message' => "Prácticas agregadas correctamente."
    );
    echo json_encode($response);
}

// Cerrar la conexión
$conn->close();
?>
