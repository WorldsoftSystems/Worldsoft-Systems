<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_especialidad = $_POST['id_especialidad'];
    $desc_especialidad = $_POST['desc_especialidad'];

    // Consulta SQL para actualizar la especialidad
    $sql = "UPDATE especialidad SET desc_especialidad = ? WHERE id_especialidad = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros (el orden y tipos deben ser correctos: "s" para string y "i" para integer)
    $stmt->bind_param("si", $desc_especialidad, $id_especialidad);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Redirigir a la página de especialidades después de actualizar con un parámetro de éxito
        header("Location: ./crearEspecialidad.php?success=true");
        exit();
    } else {
        // Manejar el error
        echo "Error al actualizar la especialidad: " . $conn->error;
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido";
}
?>
