<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $siglas = $_POST['siglas'];
    $razon_social = $_POST['razon_social'];

    // Consulta SQL para actualizar la especialidad
    $sql = "UPDATE obra_social SET siglas = ?, razon_social = ? WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros (el orden y tipos deben ser correctos: "s" para string y "i" para integer)
    $stmt->bind_param("ssi", $siglas, $razon_social, $id);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Redirigir a la página de especialidades después de actualizar con un parámetro de éxito
        header("Location: ./obraSocial.php?success=true");
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
