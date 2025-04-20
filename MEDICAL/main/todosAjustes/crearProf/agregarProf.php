<?php
// Incluir el archivo de conexión
require_once "../../conexion.php";

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreYapellido = $_POST["nombreYapellido"];
    $id_especialidad = $_POST["id_especialidad"];
    $matricula_p = $_POST["matricula_p"];
    $matricula_n = $_POST["matricula_n"];

    // Opcionales con fallback a null
    $domicilio = empty($_POST["domicilio"]) ? null : $_POST["domicilio"];
    $localidad = empty($_POST["localidad"]) ? null : $_POST["localidad"];
    $codigo_pos = empty($_POST["codigo_pos"]) ? null : $_POST["codigo_pos"];
    $telefono = empty($_POST["telefono"]) ? null : $_POST["telefono"];
    $email = empty($_POST["email"]) ? null : $_POST["email"];
    $tipo_doc = empty($_POST["tipo_doc"]) ? null : $_POST["tipo_doc"];
    $nro_doc = empty($_POST["nro_doc"]) ? null : $_POST["nro_doc"];

    $sql = "INSERT INTO profesional (nombreYapellido, id_especialidad, domicilio, localidad, codigo_pos, matricula_p, matricula_n, telefono, email, tipo_doc, nro_doc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssssssss",
        $nombreYapellido,
        $id_especialidad,
        $domicilio,
        $localidad,
        $codigo_pos,
        $matricula_p,
        $matricula_n,
        $telefono,
        $email,
        $tipo_doc,
        $nro_doc
    );

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Si el profesional se agregó correctamente, mostrar una alerta y recargar la página
        echo "<script>alert('Profesional agregado correctamente.'); window.location.href = './crearProf.php';</script>";
    } else {
        // Si hubo un error al agregar el profesional, mostrar una alerta con el mensaje de error y recargar la página
        echo "<script>alert('Error al agregar el profesional: " . $stmt->error . "'); window.location.href = './crearProf.php';</script>";
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
}
?>