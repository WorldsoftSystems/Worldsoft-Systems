<?php
require_once '../controlador/control_tipo_prac.php';

// Procesar el formulario de agregar nuevo tipo de práctica
if (isset($_POST['agregar'])) {
    $descripcion = $_POST['descripcion'];
    $cod_practica = $_POST['cod_practica']; // Nuevo campo

    if (agregarTipoPractica($descripcion, $cod_practica)) { // Modificado
        $_SESSION['alert_message'] = "Tipo de práctica agregado correctamente";
    } else {
        $_SESSION['alert_message'] = "Error al agregar el tipo de práctica: " . $conn->error;
    }

    header("Location: tipoPracticaPanel.php");
    exit();
}

// Procesar la solicitud de eliminar un tipo de práctica
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    if (eliminarTipoPractica($id)) {
        $_SESSION['alert_message'] = "Tipo de práctica eliminado correctamente";
    } else {
        $_SESSION['alert_message'] = "Error al eliminar el tipo de práctica: " . $conn->error;
    }

    header("Location: tipoPracticaPanel.php");
    exit();
}

// Procesar el formulario de editar tipo de práctica
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $cod_practica = $_POST['cod_practica']; // Nuevo campo

    if (actualizarTipoPractica($id, $descripcion, $cod_practica)) { // Modificado
        $_SESSION['alert_message'] = "Tipo de práctica actualizado correctamente";
    } else {
        $_SESSION['alert_message'] = "Error al actualizar el tipo de práctica: " . $conn->error;
    }

    header("Location: tipoPracticaPanel.php");
    exit();
}
?>
