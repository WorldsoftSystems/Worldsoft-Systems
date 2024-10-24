<?php
require_once "../../conexion.php"; // Incluye el archivo de conexión a la base de datos

$sql = "SELECT * FROM bot_zoe";
$result = $conn->query($sql);

$faqData = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $faqData[] = [
            'text' => $row['pregunta'],
            'answer' => $row['respuesta']
        ];
    }
}

echo json_encode($faqData); // Devolvemos el resultado en formato JSON
$conn->close();
?>
