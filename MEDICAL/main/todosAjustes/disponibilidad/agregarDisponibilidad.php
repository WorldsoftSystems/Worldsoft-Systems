<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_prof = $_POST['id_prof'];
    $respuesta = [];

    if (isset($_POST['horarios']) && is_array($_POST['horarios'])) {
        $exito = true;

        foreach ($_POST['horarios'] as $horario) {
            $dia = $horario['dia'];
            $hora_inicio = $horario['inicio'];
            $hora_fin = $horario['fin'];
            $intervalo = isset($horario['intervalo']) ? $horario['intervalo'] : 20;
            $consultorio = isset($horario['consultorio']) && $horario['consultorio'] !== '' ? $horario['consultorio'] : null;

            // Verificar si ya existe disponibilidad
            $check_sql = "SELECT id FROM disponibilidad WHERE id_prof = ? AND dia_semana = ? AND hora_inicio = ? AND hora_fin = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("isss", $id_prof, $dia, $hora_inicio, $hora_fin);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                $respuesta[] = ['error' => "Ya existe disponibilidad para $dia de $hora_inicio a $hora_fin. Por favor, edítala."];
                $exito = false;
            } else {
                // Insertar nueva disponibilidad con consultorio
                $sql = "INSERT INTO disponibilidad (id_prof, dia_semana, hora_inicio, hora_fin, intervalo, consultorio) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssis", $id_prof, $dia, $hora_inicio, $hora_fin, $intervalo, $consultorio);

                if ($stmt->execute()) {
                    $respuesta[] = ['success' => "Disponibilidad para $dia de $hora_inicio a $hora_fin guardada correctamente."];
                } else {
                    $respuesta[] = ['error' => "Error al agregar la disponibilidad para $dia: " . $stmt->error];
                    $exito = false;
                }

                $stmt->close();
            }

            $check_stmt->close();
        }

        $respuesta[] = ['status' => $exito ? 'Todos los horarios se han agregado correctamente.' : 'Hubo errores al intentar agregar algunos horarios.'];
    } else {
        $respuesta[] = ['error' => 'No se recibieron horarios válidos.'];
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

$conn->close();
?>
