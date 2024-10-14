<?php
require_once "../conexion.php";

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recuperar las imágenes de la base de datos
$sql = "SELECT id, img FROM test";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mostrar las imágenes
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Mostrar Imágenes</title>
        <style>
            .gallery {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            .gallery img {
                max-width: 100px;
                max-height: 100px;
                object-fit: cover;
            }
        </style>
    </head>
    <body>
        <h1>Galería de Imágenes</h1>
        <div class='gallery'>";

    while ($row = $result->fetch_assoc()) {
        $imgData = $row['img'];
        $imgSrc = 'data:image/jpeg;base64,' . base64_encode($imgData);
        echo "<img src='$imgSrc' alt='Imagen' />";
    }

    echo "</div>
    </body>
    </html>";
} else {
    echo "No hay imágenes en la base de datos.";
}

$conn->close();
?>
