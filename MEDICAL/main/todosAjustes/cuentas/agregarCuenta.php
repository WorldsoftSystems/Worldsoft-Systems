<?php
// Incluir el archivo de conexión
require_once "../../conexion.php";

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $desc_rubro = $_POST["desc_rubro"];
    $desc_cuenta = $_POST["desc_cuenta"];

    // Preparar la consulta SQL para insertar una especialidad
    $sql = "INSERT INTO cuentas (desc_rubro , desc_cuenta) VALUES (?, ?)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("is", $desc_rubro, $desc_cuenta);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Si la especialidad se agregó correctamente, mostrar una alerta y redireccionar a otra página
        echo "<script>alert('Cuenta agregado correctamente.'); window.location.href = './cuentas.php';</script>";
    } else {
        // Si hubo un error al agregar la especialidad, mostrar una alerta con el mensaje de error
        echo "<script>alert('Error al agregar la cuenta: " . $stmt->error . "');</script>";
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
}
?>
