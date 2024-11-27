<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $motivo = $_POST['motivo_evo'];
    $antecedentes = $_POST['antecedentes'];
    $estadoActual = $_POST['estado_actual'];
    $familia = $_POST['familia'];
    $diag = $_POST['evo_diag'];
    $objetivo = $_POST['objetivo'];
    $duracion = $_POST['duracion'];
    $frecuencia = $_POST['frecuencia'];
    $fecha = $_POST['evoFecha'];


    $sql = "UPDATE evoluciones_amb SET motivo = ?, antecedentes = ?, estado_actual = ?, familia = ?, diag = ?, objetivo = ?, duracion = ?, frecuencia = ?, fecha = ? WHERE id = ?";

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