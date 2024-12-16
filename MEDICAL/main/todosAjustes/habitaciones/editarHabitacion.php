<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $num = $_POST['num'];
    $piso = $_POST['piso'];
    $c_camas = $_POST['c_camas'];

    // Consulta SQL para actualizar la especialidad
    $sql = "UPDATE habitaciones SET num = ?, piso = ?, c_camas= ? WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros (el orden y tipos deben ser correctos: "s" para string y "i" para integer)
    $stmt->bind_param("sssi", $num, $piso, $c_camas, $id);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Redirigir a la página de especialidades después de actualizar con un parámetro de éxito
        header("Location: ./habitaciones.php?success=true");
        exit();
    } else {
        // Manejar el error
        echo "Error al actualizar habitacion: " . $conn->error;
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido";
}
?>
