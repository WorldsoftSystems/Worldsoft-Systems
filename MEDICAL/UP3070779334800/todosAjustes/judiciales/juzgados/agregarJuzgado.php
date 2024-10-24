<?php
// Incluir el archivo de conexión
require_once "../../../conexion.php";

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nro_juzgado = $_POST["nro_juzgado"];
    $descripcion = $_POST["descripcion"];
    $calle = $_POST["calle"];
    $numero = $_POST["numero"];
    $localidad = $_POST["localidad"];
    $provincia = $_POST["provincia"];
    $c_postal = $_POST["c_postal"];
    $tribunal = $_POST["tribunal"];

    // Preparar la consulta SQL para insertar un juzgado
    $sql = "INSERT INTO juzgado (nro_juzgado, descripcion, calle, numero, localidad, provincia, c_postal, tribunal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("ssssssss", $nro_juzgado, $descripcion, $calle, $numero, $localidad, $provincia, $c_postal, $tribunal);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Si el juzgado se agregó correctamente, mostrar una alerta y redireccionar a otra página
        echo "<script>alert('Juzgado agregado correctamente.'); window.location.href = './juzgados.php';</script>";
    } else {
        // Si hubo un error al agregar el juzgado, mostrar una alerta con el mensaje de error
        echo "<script>alert('Error al agregar juzgado: " . $stmt->error . "');</script>";
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
}
?>
