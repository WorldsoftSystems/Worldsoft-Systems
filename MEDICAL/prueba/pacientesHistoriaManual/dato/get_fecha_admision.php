<?php
require_once "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Asegúrate de que el parámetro 'id' esté presente
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Convierte el id a un entero para evitar inyecciones SQL

        // Preparar la consulta SQL
        $sql = "SELECT admision FROM paciente WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Vincular parámetros
            $stmt->bind_param("i", $id);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                // Obtener el resultado
                if ($data = $result->fetch_assoc()) {
                    echo json_encode(['fecha_admision' => $data['admision']]);
                } else {
                    echo json_encode(['fecha_admision' => null]);
                }
            } else {
                // En caso de error en la ejecución
                echo json_encode(['error' => 'Error al ejecutar la consulta: ' . $stmt->error]);
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            // En caso de error en la preparación de la consulta
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
        }

    } else {
        // Si el id no está presente
        echo json_encode(['error' => 'ID no proporcionado']);
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si el método de solicitud no es GET
    echo json_encode(['error' => 'Método de solicitud no permitido']);
}
?>
