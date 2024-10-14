<?php
require_once "../conexion.php";

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado un archivo
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $fileTmpPath = $_FILES['image']['tmp_name'];

    // Leer el contenido del archivo
    $fileData = file_get_contents($fileTmpPath);

    if ($fileData === false) {
        die("Error al leer el archivo.");
    }

    // Insertar el archivo en la base de datos
    $sql = "INSERT INTO test (img) VALUES (?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Usar 'b' para indicar que se está pasando un tipo de dato binario
    $null = NULL; // Necesario para indicar que el valor es NULL
    $stmt->bind_param('b', $null); // 'b' para tipo de dato binario

    // Enviar datos binarios usando send_long_data
    $stmt->send_long_data(0, $fileData);

    if ($stmt->execute()) {
        echo "Imagen cargada y guardada exitosamente.";
    } else {
        echo "Error al guardar la imagen en la base de datos: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No se ha enviado ningún archivo o hay un error en la carga. Código de error: " . $_FILES['image']['error'];
}

$conn->close();
?>
