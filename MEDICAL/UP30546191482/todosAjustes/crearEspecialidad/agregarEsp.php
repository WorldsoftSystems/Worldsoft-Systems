<?php
// Incluir el archivo de conexión
require_once "../../conexion.php";

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $desc_especialidad = $_POST["desc_especialidad"];

    // Preparar la consulta SQL para insertar una especialidad
    $sql = "INSERT INTO especialidad (desc_especialidad) VALUES (?)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("s", $desc_especialidad); // El tipo de parámetro debería ser "s" (string) para desc_especialidad

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Si la especialidad se agregó correctamente, mostrar una alerta y redireccionar a otra página
        echo "<script>alert('Especialidad agregada correctamente.'); window.location.href = './crearEspecialidad.php';</script>";
    } else {
        // Si hubo un error al agregar la especialidad, mostrar una alerta con el mensaje de error
        echo "<script>alert('Error al agregar la especialidad: " . $stmt->error . "');</script>";
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
}
?>
