<?php
// Importar la clase PhpSpreadsheet
require '../vendor/autoload.php';

// Cargar el controlador de pacientes
require_once '../controlador/control_paciente.php';

// Obtener los parámetros de filtro de la URL
$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';
$profesional = isset($_GET['profesional']) ? $_GET['profesional'] : '';

// Crear un nuevo objeto PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear una instancia de la hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establecer los encabezados de columna
$sheet->setCellValue('A1', 'Nombre y Apellido');
$sheet->setCellValue('B1', 'Beneficio');
$sheet->setCellValue('C1', 'Código de Profesional');
$sheet->setCellValue('D1', 'Especialidad de Profesional');
$sheet->setCellValue('E1', 'Código de Práctica');
$sheet->setCellValue('F1', 'Fecha');
$sheet->setCellValue('G1', 'Código de Diagnóstico');
$sheet->setCellValue('H1', 'Estado');


// Obtener datos filtrados de la base de datos
$pacientes = obtenerPacientesConFiltro($fecha_desde, $fecha_hasta, $profesional);

// Inicializar la fila
$row = 2;

// Llenar la hoja de cálculo con los datos de los pacientes filtrados
foreach ($pacientes as $paciente) {
    $sheet->setCellValue('A' . $row, $paciente["nombreYapellido"]);
    $sheet->setCellValue('B' . $row, $paciente["benef"]);
    $sheet->setCellValue('C' . $row, obtenerNombreProfesional($paciente["cod_prof"]));
    $sheet->setCellValue('D' . $row, obtenerEspecialidadProfesional($paciente["cod_prof"]));
    $sheet->setCellValue('E' . $row, $paciente["cod_practica"]);
    $sheet->setCellValue('F' . $row, date('d/m/Y H:i:s', strtotime($paciente["fecha"])));
    $sheet->setCellValue('G' . $row, $paciente["cod_diag"]);
    $sheet->setCellValue('H' . $row, $paciente["cargado"]);
    $row++;
}

// Configurar el tipo de contenido y el nombre del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_pacientes.xlsx"');
header('Cache-Control: max-age=0');

// Crear un objeto Writer para guardar el archivo
$writer = new Xlsx($spreadsheet);
// Guardar el archivo en la salida
$writer->save('php://output');
