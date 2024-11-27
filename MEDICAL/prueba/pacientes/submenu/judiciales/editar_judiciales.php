<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $fecha = $_POST['fecha'];
    $juzgado = $_POST['juzgado'];
    $secretaria = $_POST['secretaria'];
    $curaduria = $_POST['curaduria'];
    $t_juicio = $_POST['t_juicio'];
    $obs = $_POST['judiObs'];

    $sql = "UPDATE judiciales SET juzgado = ?, secre = ?, cura = ?, t_juicio = ?, 	vencimiento = ?, obs = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiissi", $juzgado,$secretaria,$curaduria,$t_juicio,$fecha,$obs, $id);

    if ($stmt->execute()) {
        echo "Práctica actualizada correctamente";
    } else {
        echo "Error al actualizar la práctica: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>