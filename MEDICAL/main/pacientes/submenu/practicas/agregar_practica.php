<?php
require_once "../../../conexion.php";

// Función para formatear fechas en formato DD/MM/YYYY
function formatDateToArg($date)
{
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

// Validar que la cantidad no sea 0
if ($cant <= 0) {
    $response = array(
        'status' => 'error',
        'message' => "La cantidad de prácticas debe ser mayor a 0."
    );
    echo json_encode($response);
    exit; // Salir del script si la cantidad no es válida
}

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

// Obtener la fecha de vencimiento más alta para el paciente
$sqlVencimiento = "SELECT MAX(fecha_vencimiento) FROM paci_op WHERE id_paciente = ?";
$stmtVenc = $conn->prepare($sqlVencimiento);
$stmtVenc->bind_param('i', $idPaciente);
$stmtVenc->execute();
$stmtVenc->bind_result($fechaVencimiento);
$stmtVenc->fetch();
$stmtVenc->close();

// Formatear la fecha de vencimiento para mostrarla en mensajes
$fechaVencimientoArg = formatDateToArg($fechaVencimiento);


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

    // Validar que la fecha no sea posterior a la fecha de vencimiento
    if ($fechaVencimiento && $fecha > $fechaVencimiento) {
        $error = true;
        $fechaPracticaArg = formatDateToArg($fecha);
        $response = array(
            'status' => 'error',
            'message' => "La fecha de práctica ($fechaPracticaArg) no puede ser posterior a la fecha de vencimiento de la orden ($fechaVencimientoArg)."
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