<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $idPaciente = $_POST['id_paciente'];
    $prof = $_POST['evoProf'];
    $frecuencia = $_POST['frecuencia'];
    $fecha = $_POST['evoFecha'];


    $sql = "UPDATE evoluciones_amb SET frecuencia = ?, fecha = ?, id_prof = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $frecuencia, $fecha,$prof, $id);


    if ($stmt->execute()) {
        echo "Evolucion actualizada correctamente";
    } else {
        echo "Error al actualizar Evolucion: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>