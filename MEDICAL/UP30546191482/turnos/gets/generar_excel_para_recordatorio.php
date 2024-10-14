<?php
// Conexión a la base de datos
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los parámetros de la URL
$profesionalId = $_GET['profesional'];
$fechaDesde = $_GET['fechaDesde'];
$fechaHasta = $_GET['fechaHasta'];

// Validar que los parámetros existan
if (!$profesionalId || !$fechaDesde || !$fechaHasta) {
    die("Faltan parámetros.");
}


// Consulta para obtener los turnos filtrados por profesional y fechas
if ($profesionalId === 'all') {
    $sql = "SELECT t.fecha, t.hora, paci.nombre AS paciente, p.nombreYapellido as profesional, a.descripcion AS motivo, t.llego, t.atendido, t.observaciones, paci.telefono AS numero
            FROM turnos t
            LEFT JOIN profesional p ON t.id_prof = p.id_prof
            LEFT JOIN paciente paci ON paci.id = t.paciente
            LEFT JOIN actividades a ON a.id = t.motivo
            WHERE t.fecha BETWEEN ? AND ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fechaDesde, $fechaHasta);
} else {
    $sql = "SELECT t.fecha, t.hora, paci.nombre AS paciente, p.nombreYapellido as profesional, a.descripcion AS motivo, t.llego, t.atendido, t.observaciones, paci.telefono AS numero
            FROM turnos t
            LEFT JOIN profesional p ON t.id_prof = p.id_prof
            LEFT JOIN paciente paci ON paci.id = t.paciente
            LEFT JOIN actividades a ON a.id = t.motivo
            WHERE t.id_prof = ? AND t.fecha BETWEEN ? AND ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $profesionalId, $fechaDesde, $fechaHasta);
}

$stmt->execute();
$resultado = $stmt->get_result();

// Incluye la librería PHPSpreadsheet para generar Excel
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear el documento de Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de la tabla
$sheet->setCellValue('A1', 'FECHA');
$sheet->setCellValue('B1', 'HORA');
$sheet->setCellValue('C1', 'PACIENTE');
$sheet->setCellValue('D1', 'PROFESIONAL');
$sheet->setCellValue('E1', 'MOTIVO');
$sheet->setCellValue('F1', 'LLEGO');
$sheet->setCellValue('G1', 'ATENDIDO');
$sheet->setCellValue('H1', 'OBSERVACIONES');
$sheet->setCellValue('I1', 'NUMERO');

// Inicializamos el nombre del profesional como vacío
$nombreProfesional = '';

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    $fila = 2; // Iniciar desde la fila 2 ya que la 1 es para los encabezados
    while ($row = $resultado->fetch_assoc()) {

        // Si el nombre del profesional no se ha asignado aún, lo tomamos del primer resultado
        if (empty($nombreProfesional)) {
            $nombreProfesional = $row['profesional'];
        }

        // Formatear la fecha en dd/mm/aaaa
        $fechaFormateada = date("d/m/Y", strtotime($row['fecha']));

        // Formatear la hora en HH:mm (sin segundos)
        $horaFormateada = date("H:i", strtotime($row['hora']));

        $sheet->setCellValue('A' . $fila, $fechaFormateada);
        $sheet->setCellValue('B' . $fila, $horaFormateada);
        $sheet->setCellValue('C' . $fila, $row['paciente']);
        $sheet->setCellValue('D' . $fila, $row['profesional']);
        $sheet->setCellValue('E' . $fila, $row['motivo']);
        $sheet->setCellValue('F' . $fila, $row['llego']);
        $sheet->setCellValue('G' . $fila, $row['atendido']);
        $sheet->setCellValue('H' . $fila, $row['observaciones']);
        $sheet->setCellValue('I' . $fila, $row['numero']);
        
        $fila++;
    }
}

// Si el profesional es 'all', usamos "todos" como el nombre del profesional
if ($profesionalId === 'all') {
    $nombreProfesional = 'todos';
} else {
    // Si no se obtuvo nombre del profesional, lo dejamos como 'desconocido'
    if (empty($nombreProfesional)) {
        $nombreProfesional = 'desconocido';
    }

    // Limpiar el nombre del profesional para el nombre del archivo
    $nombreProfesional = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombreProfesional);
}

// Descargar el archivo Excel con el nombre del profesional
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="turnos_de_' . $nombreProfesional . '.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');

// Cerrar la conexión
$conn->close();
?>