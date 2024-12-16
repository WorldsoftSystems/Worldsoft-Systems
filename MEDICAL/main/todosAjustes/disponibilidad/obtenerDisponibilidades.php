<?php
require_once "../../conexion.php";

if (isset($_GET['id_prof'])) {
    $id_prof = intval($_GET['id_prof']);

    $sql = "SELECT * FROM disponibilidad WHERE id_prof = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_prof);
    $stmt->execute();
    $result = $stmt->get_result();

    $disponibilidades = [];
    while ($row = $result->fetch_assoc()) {
        $disponibilidades[] = $row;
    }

    echo json_encode($disponibilidades);
    exit();
}

echo json_encode([]);
exit();
?>
