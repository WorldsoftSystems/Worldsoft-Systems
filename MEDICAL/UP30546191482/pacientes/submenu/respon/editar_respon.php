<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $nombre = $_POST['respon_nombre'];
    $tel = $_POST['respon_tel'];
    $tipo_familiar = $_POST['respon_parent'];
    $dni = $_POST['respon_dni'];
    $dom = $_POST['respon_dom'];
    $localidad = $_POST['respon_locali'];

    $sql = "UPDATE responsable SET nombreYapellido = ?, tel = ?, tipo_familiar = ?, dni = ?, dom = ?, localidad = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiisi", $nombre,$tel,$tipo_familiar,$dni,$dom,$localidad, $id);

    if ($stmt->execute()) {
        echo "Responsable actualizada correctamente";
    } else {
        echo "Error al actualizar el responsable: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
