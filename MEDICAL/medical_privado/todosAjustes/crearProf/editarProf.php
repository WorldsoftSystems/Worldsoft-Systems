<?php
session_start();
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_prof = $_POST['id_prof'];
    $nombreYapellido = $_POST['nombreYapellido'];
    $id_especialidad = $_POST['id_especialidad'];
    $domicilio = $_POST['domicilio'];
    $localidad = $_POST['localidad'];
    $codigo_pos = $_POST['codigo_pos'];
    $matricula_p = $_POST['matricula_p'];
    $matricula_n = $_POST['matricula_n'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $tipo_doc = $_POST['tipo_doc'];
    $nro_doc = $_POST['nro_doc'];

    // Preparar la consulta SQL para actualizar los datos del profesional
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
