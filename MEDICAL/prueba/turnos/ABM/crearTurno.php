<?php
include('../../conexion.php');

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$id_prof = $_POST['id_prof_input'];
$fecha = $_POST['fecha_input']; // Fecha en formato DD/MM/YYYY
$hora = $_POST['hora_input'];
$paciente = $_POST['paciente_id'];
$motivo = $_POST['motivo'];
$llego = $_POST['llego'];
$atendido = $_POST['atendido'];
$observaciones = $_POST['observaciones'];

// Convertir la fecha al formato YYYY-MM-DD
$date = DateTime::createFromFormat('d/m/Y', $fecha);
$fechaFormateada = $date ? $date->format('Y-m-d') : null;

// Preparar y vincular
$stmt = $conn->prepare("INSERT INTO turnos (fecha, hora, paciente, id_prof, motivo, llego, atendido, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssiiisss", $fechaFormateada, $hora, $paciente, $id_prof, $motivo, $llego, $atendido, $observaciones);

// Ejecutar la declaración
if ($stmt->execute()) {
    echo "Nuevo turno creado exitosamente";

    // Obtener el código de la actividad usando el motivo
    $motivo_stmt = $conn->prepare("SELECT codigo FROM actividades WHERE id = ?");
    $motivo_stmt->bind_param("i", $motivo);
    $motivo_stmt->execute();
    $motivo_stmt->bind_result($codigo_motivo);
    $motivo_stmt->fetch();
    $motivo_stmt->close();

    // Insertar en la tabla practicas si llego y atendido son ambos "SI" y el código no está en la lista excluida
    if ($llego === 'SI' && $atendido === 'SI' && $codigo_motivo !== '521001' && $codigo_motivo !== '520101') {
        $cant_t = 1;

        // Verificar si ya existe una práctica con la misma fecha, hora y motivo
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM practicas WHERE id_paciente = ? AND fecha = ? AND hora = ? AND actividad = ?");
        $check_stmt->bind_param("issi", $paciente, $fechaFormateada, $hora, $motivo);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        // Si no hay duplicado, insertar en practicas
        if ($count == 0) {
            $insert_stmt = $conn->prepare("INSERT INTO practicas (id_paciente, fecha, hora, profesional, actividad, cant) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("issiii", $paciente, $fechaFormateada, $hora, $id_prof, $motivo, $cant_t);

            if ($insert_stmt->execute()) {
                echo " - Registro insertado en la tabla practicas.";
            } else {
                echo "Error al insertar en practicas: " . $insert_stmt->error;
            }

            // Cerrar la declaración de inserción
            $insert_stmt->close();
        } else {
            echo " - La práctica ya existe en la tabla practicas.";
        }
    }
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
