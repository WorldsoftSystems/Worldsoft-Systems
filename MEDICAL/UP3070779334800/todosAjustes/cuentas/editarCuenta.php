<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $desc_rubro = $_POST["desc_rubro"];
    $desc_cuenta = $_POST["desc_cuenta"];

    // Consulta SQL para actualizar la especialidad
    $sql = "UPDATE cuentas SET desc_rubro = ?, desc_cuenta=? WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros (el orden y tipos deben ser correctos: "s" para string y "i" para integer)
    $stmt->bind_param("isi", $desc_rubro, $desc_cuenta, $id);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Redirigir a la página de especialidades después de actualizar con un parámetro de éxito
        header("Location: ./cuentas.php?success=true");
        exit();
    } else {
        // Manejar el error
        echo "Error al actualizar cuenta: " . $conn->error;
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido";
}
?>
