<?php
require_once "../../conexion.php";

// Obtener el id desde la solicitud GET
$id = $_GET['id'] ?? '';

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los datos de la práctica específica
$sql = "SELECT t.*, 
               CONCAT(paci.nombre, ' - Afiliado:', paci.benef, '/', paci.parentesco, ' - ',os.siglas,' - Tel:',paci.telefono) AS nombre_paciente,
               CONCAT(a.codigo, ' - ', a.descripcion) AS motivo_full,
               p.nombreYapellido AS nom_prof
        FROM turnos t
        LEFT JOIN paciente paci ON paci.id = t.paciente
        LEFT JOIN actividades a ON a.id = t.motivo
        LEFT JOIN profesional p ON p.id_prof = t.id_prof
        LEFT JOIN obra_social os ON os.id = paci.obra_social
         WHERE t.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Procesar los resultados de la consulta
$turno = $result->fetch_assoc();

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($turno);
?>