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
    $sheet->setCellValue('A1', 'Documento');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Serial');
    $sheet->setCellValue('D1', 'Marca');
    $sheet->setCellValue('E1', 'Color');
    $sheet->setCellValue('F1', 'Tipo de Dispositivo');
    $sheet->setCellValue('G1', 'Estado');

    // Fill data
    $row = 2;
    foreach ($resultado as $row_data) {
        $sheet->setCellValue('A' . $row, $row_data['documento']);
        $sheet->setCellValue('B' . $row, $row_data['nombres']);
        $sheet->setCellValue('C' . $row, $row_data['serial']);
        $sheet->setCellValue('D' . $row, $row_data['nom_marca']);
        $sheet->setCellValue('E' . $row, $row_data['nom_color']);
        $sheet->setCellValue('F' . $row, $row_data['nom_dispositivo']);
        $sheet->setCellValue('G' . $row, $row_data['nom_estado']);
        $row++;
    }

    // Save Excel file
    $writer = new Xlsx($spreadsheet);
    $filename = 'reporte_dispositivos.xlsx';
    $writer->save($filename);

    return $filename;
}

// Fetch device data from the database
$por_pagina = 5;
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}
$empieza = ($pagina - 1) * $por_pagina;
$sql = $conexion->prepare("SELECT * FROM dispositivos 
                         LEFT JOIN marcas ON dispositivos.id_marca = marcas.id_marca 
                         LEFT JOIN usuario ON dispositivos.documento = usuario.documento 
                         LEFT JOIN color ON dispositivos.id_color = color.id_color 
                         LEFT JOIN tipo_dispositivo ON dispositivos.id_tipo_dispositivo = tipo_dispositivo.id_tipo_dispositivo 
                         LEFT JOIN estados ON dispositivos.estado = estados.id_estados
                         ORDER BY usuario.documento LIMIT $empieza, $por_pagina");
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
