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

// Obtener la hora de admisión del paciente
$sqlAdmision = "SELECT hora_admision FROM paciente WHERE id = ?";
$stmtAdmision = $conn->prepare($sqlAdmision);
$stmtAdmision->bind_param('i', $idPaciente);
$stmtAdmision->execute();
$stmtAdmision->bind_result($horaAdmision);
$stmtAdmision->fetch();
$stmtAdmision->close();

// Obtener la fecha más alta de `paci_modalidad`
$sqlModalidad = "SELECT MAX(fecha) FROM paci_modalidad WHERE id_paciente = ?";
$stmtModalidad = $conn->prepare($sqlModalidad);
$stmtModalidad->bind_param('i', $idPaciente);
$stmtModalidad->execute();
$stmtModalidad->bind_result($fechaModalidad);
$stmtModalidad->fetch();
$stmtModalidad->close();

// Formatear la fecha para mostrarla en mensajes
$fechaModalidadArg = formatDateToArg($fechaModalidad);
$error = false;

foreach ($fechas as $fecha) {
    // Validar que la fecha no sea anterior a la última fecha de `paci_modalidad`
    if ($fecha < $fechaModalidad) {
        $error = true;
        $fechaPracticaArg = formatDateToArg($fecha);
        $response = array(
            'status' => 'error',
            'message' => "La fecha de práctica ($fechaPracticaArg) no puede ser anterior a la última fecha de modalidad registrada ($fechaModalidadArg)."
        );
        echo json_encode($response);
        exit;
    }

    // Si la práctica es el mismo día de la fecha de modalidad, validar la hora
    if ($fecha == $fechaModalidad && $hora < $horaAdmision) {
        $error = true;
        $fechaPracticaArg = formatDateToArg($fecha);
        $response = array(
            'status' => 'error',
            'message' => "La práctica en la fecha ($fechaPracticaArg) no puede ser antes de la hora de admisión ($horaAdmision)."
        );
        echo json_encode($response);
        exit;
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
