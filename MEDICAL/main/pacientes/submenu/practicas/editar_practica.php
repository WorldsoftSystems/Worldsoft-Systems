<?php
require_once "../../../conexion.php";

header('Content-Type: application/json'); // Indicar que la respuesta será en JSON

$response = array(); // Array para almacenar la respuesta

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Decodificar el JSON de 'fechas' y verificar que se decodificó correctamente
    $fechas = json_decode($_POST['fechas'], true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($fechas) && !empty($fechas)) {
        $fecha = $fechas[0]; // Tomar la primera fecha del array decodificado
    } else {
        $response['status'] = 'error';
        $response['message'] = "No se recibió un formato de fechas válido o el array está vacío.";
        echo json_encode($response);
        exit;
    }

    $hora = $_POST['hora'];
    $profesional = $_POST['profesional'];
    $actividad = $_POST['actividad'];
    $cant = $_POST['cant'];

    // Validar que la cantidad no sea 0
    if ($cant <= 0) {
        $response = array(
            'status' => 'error',
            'message' => "La cantidad de prácticas debe ser mayor a 0."
        );
        echo json_encode($response);
        exit; // Salir del script si la cantidad no es válida
    }

    // Obtener id_paciente de la práctica que se está editando
    $sqlGetPaciente = "SELECT id_paciente FROM practicas WHERE id = ?";
    $stmtPaciente = $conn->prepare($sqlGetPaciente);
    $stmtPaciente->bind_param("i", $id);
    $stmtPaciente->execute();
    $stmtPaciente->bind_result($idPaciente);
    $stmtPaciente->fetch();
    $stmtPaciente->close();

    // Obtener la hora de admisión del paciente
    $sqlAdmision = "SELECT hora_admision FROM paciente WHERE id = ?";
    $stmtAdmision = $conn->prepare($sqlAdmision);
    $stmtAdmision->bind_param("i", $idPaciente);
    $stmtAdmision->execute();
    $stmtAdmision->bind_result($horaAdmision);
    $stmtAdmision->fetch();
    $stmtAdmision->close();

    // Obtener la última fecha de `paci_modalidad`
    $sqlModalidad = "SELECT MAX(fecha) FROM paci_modalidad WHERE id_paciente = ?";
    $stmtModalidad = $conn->prepare($sqlModalidad);
    $stmtModalidad->bind_param("i", $idPaciente);
    $stmtModalidad->execute();
    $stmtModalidad->bind_result($fechaModalidad);
    $stmtModalidad->fetch();
    $stmtModalidad->close();

    // Formatear la fecha para mostrarla en los mensajes
    function formatDateToArg($date)
    {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        return $dateObj ? $dateObj->format('d/m/Y') : $date;
    }

    $fechaModalidadArg = formatDateToArg($fecha);

    // Validar que la nueva fecha no sea anterior a la última fecha de modalidad
    if ($fecha < $fechaModalidad) {
        $response['status'] = 'error';
        $response['message'] = "La fecha de la práctica ($fechaModalidadArg) no puede ser anterior a la última fecha de modalidad registrada.";
        echo json_encode($response);
        exit;
    }

    // Obtener la fecha de vencimiento más alta para el paciente
    $sqlVencimiento = "SELECT MAX(fecha_vencimiento) FROM paci_op WHERE id_paciente = ?";
    $stmtVenc = $conn->prepare($sqlVencimiento);
    $stmtVenc->bind_param("i", $idPaciente);
    $stmtVenc->execute();
    $stmtVenc->bind_result($fechaVencimiento);
    $stmtVenc->fetch();
    $stmtVenc->close();

    $fechaVencimientoArg = formatDateToArg($fechaVencimiento);

    // Validar que la fecha no sea posterior a la fecha de vencimiento
    if ($fechaVencimiento && $fecha > $fechaVencimiento) {
        $response['status'] = 'error';
        $response['message'] = "La fecha de la práctica (" . formatDateToArg($fecha) . ") no puede ser posterior a la fecha de vencimiento de la orden ($fechaVencimientoArg).";
        echo json_encode($response);
        exit;
    }


    // Si la nueva fecha es igual a la última fecha de modalidad, validar la hora
    if ($fecha == $fechaModalidad && $hora < $horaAdmision) {
        $response['status'] = 'error';
        $response['message'] = "La hora de la práctica ($hora) no puede ser anterior a la hora de admisión ($horaAdmision) en la fecha seleccionada.";
        echo json_encode($response);
        exit;
    }

    // Si pasa las validaciones, actualizar la práctica
    $sql = "UPDATE practicas SET fecha = ?, hora = ?, profesional = ?, actividad = ?, cant = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiisi", $fecha, $hora, $profesional, $actividad, $cant, $id);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = "Práctica actualizada correctamente.";
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error al actualizar la práctica: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
}
?>