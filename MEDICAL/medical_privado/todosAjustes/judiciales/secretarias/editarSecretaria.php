<?php
require_once "../../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $nro_secretaria = $_POST['nro_secretaria'];
    $descripcion = $_POST['descripcion'];
    $calle = $_POST['calle'];
    $numero = $_POST['numero'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $c_postal = $_POST['c_postal'];
    $tribunal = $_POST['tribunal'];

    // Consulta SQL para actualizar el juzgado
    $sql = "UPDATE secretaria SET nro_secretaria = ?, descripcion = ?, calle = ?, numero = ?, localidad = ?, provincia = ?, c_postal = ?, tribunal = ? WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros (el orden y tipos deben ser correctos: "s" para string y "i" para integer)
    $stmt->bind_param("ssssssssi", $nro_secretaria, $descripcion, $calle, $numero, $localidad, $provincia, $c_postal, $tribunal, $id);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Redirigir a la página de juzgados después de actualizar con un parámetro de éxito
        header("Location: ./secretarias.php?success=true");
        exit();
    } else {
        // Manejar el error
        echo "Error al actualizar secretaria: " . $conn->error;
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido";
}
?>
