<?php
// Incluir el archivo de conexión
require_once "../../conexion.php";

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombreYapellido = $_POST["nombreYapellido"];
    $id_especialidad = $_POST["id_especialidad"];
    $domicilio = $_POST["domicilio"];
    $localidad = $_POST["localidad"];
    $codigo_pos = $_POST["codigo_pos"];
    $matricula_p = $_POST["matricula_p"];
    $matricula_n = $_POST["matricula_n"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $tipo_doc = $_POST['tipo_doc'];
    $nro_doc = $_POST['nro_doc'];

    // Preparar la consulta SQL para insertar un profesional
    $sql = "INSERT INTO profesional (nombreYapellido, id_especialidad, domicilio, localidad, codigo_pos, matricula_p, matricula_n, telefono, email, tipo_doc, nro_doc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("sssssssss", $nombreYapellido, $id_especialidad, $domicilio, $localidad, $codigo_pos, $matricula_p, $matricula_n, $telefono, $email, $tipo_doc, $nro_doc );

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
