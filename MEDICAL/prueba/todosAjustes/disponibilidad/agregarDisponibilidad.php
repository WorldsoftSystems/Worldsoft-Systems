<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_prof = $_POST['id_prof']; // Obtén el id del profesional

    // Inicializar un array para las respuestas
    $respuesta = [];

    // Verificar si se han enviado los datos de los horarios
    if (isset($_POST['horarios']) && is_array($_POST['horarios'])) {
        $exito = true; // Variable para controlar si todo salió bien

        // Iterar sobre cada horario recibido
        foreach ($_POST['horarios'] as $horario) {
            // Extraer el día, la hora de inicio, la hora de fin y el intervalo
            $dia = $horario['dia'];
            $hora_inicio = $horario['inicio'];
            $hora_fin = $horario['fin'];
            $intervalo = isset($horario['intervalo']) ? $horario['intervalo'] : 20;

            // Verificar si ya existe disponibilidad para ese día y profesional
            $check_sql = "SELECT id FROM disponibilidad WHERE id_prof = ? AND dia_semana = ? AND hora_inicio = ? AND hora_fin = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("isss", $id_prof, $dia, $hora_inicio, $hora_fin);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                // Si ya existe la misma franja horaria, retornar error
                $respuesta[] = ['error' => "Ya existe disponibilidad para $dia de $hora_inicio a $hora_fin. Por favor, edítala."];
                $exito = false;
            } else {
                // Si no existe, insertar el nuevo horario
                $sql = "INSERT INTO disponibilidad (id_prof, dia_semana, hora_inicio, hora_fin, intervalo) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issss", $id_prof, $dia, $hora_inicio, $hora_fin, $intervalo);

                if ($stmt->execute()) {
                    $respuesta[] = ['success' => "Disponibilidad para $dia de $hora_inicio a $hora_fin guardada correctamente."];
                } else {
                    $respuesta[] = ['error' => "Error al agregar la disponibilidad para $dia: " . $stmt->error];
                    $exito = false;
                }

                $stmt->close();
            }
        }

        // Si todo fue exitoso, devolver un mensaje de éxito general
        if ($exito) {
            $respuesta[] = ['success' => 'Todos los horarios se han agregado correctamente.'];
        } else {
            // Si hubo algún error, devolver error general
            $respuesta[] = ['error' => 'Hubo errores al intentar agregar algunos horarios.'];
        }
    } else {
        $respuesta[] = ['error' => 'No se recibieron horarios válidos.'];
    }

    // Retornar la respuesta en formato JSON
    header('Content-Type: application/json'); // Indicar que la respuesta es JSON
    echo json_encode($respuesta);
    exit();
}

$conn->close();
?>
