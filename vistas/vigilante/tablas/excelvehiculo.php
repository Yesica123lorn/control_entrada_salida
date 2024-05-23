<?php
require_once("../../../db/conexion.php");
$db = new Database();
$conexion = $db->conectar();
require_once("../../../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function generarExcel($resultado)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Column headers
    $sheet->setCellValue('A1', 'id_placa');
    $sheet->setCellValue('B1', 'id_marca');
    $sheet->setCellValue('C1', 'id_color');
    $sheet->setCellValue('D1', 'id_tipo_vehiculo');
    $sheet->setCellValue('E1', 'documento');
    $sheet->setCellValue('F1', 'estado');

    // Fill data
    $row = 2;
    foreach ($resultado as $row_data) {
        $sheet->setCellValue('A' . $row, $row_data['id_placa']);
        $sheet->setCellValue('B' . $row, $row_data['id_marca']);
        $sheet->setCellValue('C' . $row, $row_data['id_color']);
        $sheet->setCellValue('D' . $row, $row_data['id_tipo_vehiculo']);
        $sheet->setCellValue('E' . $row, $row_data['documento']);
        $sheet->setCellValue('F' . $row, $row_data['estado']);
        $row++;
    }

    // Save Excel file
    $writer = new Xlsx($spreadsheet);
    $filename = 'reporte_vehiculos.xlsx';
    $writer->save($filename);

    return $filename;
}

// Fetch vehicle data from the database
$por_pagina = 5;
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}
$empieza = ($pagina - 1) * $por_pagina;
$sql = $conexion->prepare("SELECT * FROM vehiculos 
                         ORDER BY id_placa LIMIT $empieza, $por_pagina");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

// Generate Excel file
$archivo_excel = generarExcel($resultado);

// Download Excel file
// Descargar el archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $archivo_excel . '"');
header('Cache-Control: max-age=0');
readfile($archivo_excel);
exit();
?>
