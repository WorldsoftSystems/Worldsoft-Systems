<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $motivo = $_POST['motivo_evo_int'];
    $antecedentes = $_POST['antecedentes_int'];
    $estadoActual = $_POST['estado_actual_int'];
    $familia = $_POST['familia_int'];
    $diag = $_POST['evo_diag_int'];
    $objetivo = $_POST['objetivo_int'];
    $duracion = $_POST['duracion_int'];
    $frecuencia = $_POST['frecuencia_int'];
    $fecha = $_POST['evoFecha_int'];


    $sql = "UPDATE evoluciones_int SET motivo = ?, antecedentes = ?, estado_actual = ?, familia = ?, diag = ?, objetivo = ?, duracion = ?, frecuencia = ?, fecha = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssissssi", $motivo, $antecedentes, $estadoActual, $familia, $diag, $objetivo, $duracion, $frecuencia, $fecha, $id);


    if ($stmt->execute()) {
        echo "Evolucion actualizada correctamente";
    } else {
        echo "Error al actualizar Evolucion: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>