<?php
require_once("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();
require_once("../../../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear instancia de Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de columna
$sheet->setCellValue('A1', 'Documento');
$sheet->setCellValue('B1', 'Nombre');
$sheet->setCellValue('C1', 'Correo');
$sheet->setCellValue('D1', 'Estado');

// Obtener los datos de la tabla de usuarios
$sql = $conex->prepare("SELECT usuario.*, estados.id_estados, estados.nom_estado FROM usuario LEFT JOIN estados ON usuario.id_estados = estados.id_estados WHERE id_rol = 5 ORDER BY documento");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

// Llenar datos en la hoja
$row = 2;
foreach ($resultado as $row_data) {
    $sheet->setCellValue('A' . $row, $row_data['documento']);
    $sheet->setCellValue('B' . $row, $row_data['nombres']);
    $sheet->setCellValue('C' . $row, $row_data['correo']);
    $sheet->setCellValue('D' . $row, $row_data['nom_estado']);
    $row++;
}

// Crear el archivo Excel
$writer = new Xlsx($spreadsheet);
$filename = 'reporte.xlsx';
$writer->save($filename);

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
