<?php
session_start();
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Campos obligatorios
    $id_prof = $_POST['id_prof'];
    $nombreYapellido = $_POST['nombreYapellido'];
    $id_especialidad = $_POST['id_especialidad'];
    $matricula_p = $_POST['matricula_p'];
    $matricula_n = $_POST['matricula_n'];

    // Campos opcionales: si están vacíos, se asigna null
    $domicilio = empty($_POST['domicilio']) ? null : $_POST['domicilio'];
    $localidad = empty($_POST['localidad']) ? null : $_POST['localidad'];
    $codigo_pos = empty($_POST['codigo_pos']) ? null : $_POST['codigo_pos'];
    $telefono = empty($_POST['telefono']) ? null : $_POST['telefono'];
    $email = empty($_POST['email']) ? null : $_POST['email'];
    $tipo_doc = empty($_POST['tipo_doc']) ? null : $_POST['tipo_doc'];
    $nro_doc = empty($_POST['nro_doc']) ? null : $_POST['nro_doc'];

    // Preparar la consulta SQL
    $sql = "UPDATE profesional SET 
            nombreYapellido = ?,
            id_especialidad = ?,
            domicilio = ?,
            localidad = ?,
            codigo_pos = ?,
            matricula_p = ?,
            matricula_n = ?,
            telefono = ?,
            email = ?,
            tipo_doc = ?,
            nro_doc = ?
            WHERE id_prof = ?";
    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("ssssssssssii", $nombreYapellido, $id_especialidad, $domicilio, $localidad, $codigo_pos, $matricula_p, $matricula_n, $telefono, $email, $tipo_doc, $nro_doc, $id_prof);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Redireccionar a la página de profesionales después de la edición
        header("Location: ./crearProf.php?editado=true");
        // Después de una edición exitosa
        $_SESSION['editado'] = true;
        exit();
    } else {
        echo "Error al intentar editar el profesional.";
    }
}

// Cerrar conexión
$conn->close();
?>