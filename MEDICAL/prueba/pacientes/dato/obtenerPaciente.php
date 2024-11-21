<?php
// Conexión a la base de datos
require_once "../../conexion.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener los datos del paciente
    $sql = "SELECT p.*,o.razon_social AS obra, CONCAT(d.codigo,' ',d.descripcion) AS diag_full
            FROM paciente p
            LEFT JOIN obra_social o ON o.id = p.obra_social
            LEFT JOIN paci_diag pD ON pD.id_paciente = p.id
            LEFT JOIN diag d ON d.id = pD.codigo 
            WHERE p.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del paciente
        $paciente = $result->fetch_assoc();
        echo json_encode($paciente);
    } else {
        echo json_encode(null); // Si no se encuentra el paciente
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(null); // Si no se pasa el ID
}
?>
