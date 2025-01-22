<?php
// Conexión a la base de datos
require_once "../../conexion.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener los datos del paciente
    $sql = "SELECT 
    p.*, 
    o.razon_social AS obra, 
    CONCAT(d.codigo, ' ', d.descripcion) AS diag_full, 
    prof.nombreYapellido AS profesional, 
    m.descripcion AS modalidad,
    e.fecha_egreso AS tipo_egreso
    FROM paciente p
    LEFT JOIN obra_social o ON o.id = p.obra_social
    LEFT JOIN (
        SELECT id_paciente, codigo 
        FROM paci_diag 
        WHERE (id_paciente, fecha) IN (
            SELECT id_paciente, MAX(fecha) 
            FROM paci_diag 
            GROUP BY id_paciente
        )
    ) pD ON pD.id_paciente = p.id
    LEFT JOIN diag d ON d.id = pD.codigo
    LEFT JOIN (
        SELECT id_paciente, modalidad 
        FROM paci_modalidad 
        WHERE (id_paciente, fecha) IN (
            SELECT id_paciente, MAX(fecha) 
            FROM paci_modalidad 
            GROUP BY id_paciente
        )
    ) pM ON pM.id_paciente = p.id
    LEFT JOIN modalidad m ON m.id = pM.modalidad
    LEFT JOIN (
        SELECT id_paciente, fecha_egreso 
        FROM egresos 
        WHERE (id_paciente, fecha_egreso) IN (
            SELECT id_paciente, MAX(fecha_egreso) 
            FROM egresos 
            GROUP BY id_paciente
        )
    ) e ON e.id_paciente = p.id
    LEFT JOIN profesional prof ON prof.id_prof = p.id_prof
    WHERE p.id = ?
";
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