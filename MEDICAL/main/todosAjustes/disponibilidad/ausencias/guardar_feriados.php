<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fechas'])) {
    $fechas = explode(',', $_POST['fechas']); // Convertir fechas en array
    $motivo = $_POST['motivoFeriado'];

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $queryProfesionales = "SELECT id_prof FROM profesional";
    $resultado = $conn->query($queryProfesionales);

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $id_prof = $fila['id_prof'];

            foreach ($fechas as $fecha) {
                $fecha = trim($fecha); // Eliminar espacios en blanco

                // Convertir la fecha de dd/mm/yyyy a yyyy-mm-dd
                $fecha_mysql = DateTime::createFromFormat('d/m/Y', $fecha);
                if (!$fecha_mysql) {
                    echo "Error al convertir la fecha: $fecha<br>";
                    continue;
                }
                $fecha_mysql = $fecha_mysql->format('Y-m-d'); // Formato MySQL

                $stmt = $conn->prepare("INSERT INTO ausencias (id_prof, fecha_inicio, fecha_fin, motivo) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $id_prof, $fecha_mysql, $fecha_mysql, $motivo);

                if (!$stmt->execute()) {
                    echo "Error en la inserción: " . $stmt->error . "<br>";
                }

                $stmt->close();
            }
        }
    } else {
        echo "No se encontraron profesionales en la base de datos.";
    }

    $conn->close();
    echo "Feriados guardados exitosamente";
} else {
    echo "Error en los datos enviados.";
}
?>
