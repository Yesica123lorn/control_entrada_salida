<?php
require_once("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();
require_once("../../../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Función para generar el archivo Excel
function generarExcel($resultado)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados de columna
    $sheet->setCellValue('A1', 'Documento');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Correo');
    $sheet->setCellValue('D1', 'Estado');

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

    return $filename;
}

// Obtener los datos del usuario
$por_pagina = 5;
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}
$empieza = ($pagina - 1) * $por_pagina;
$sql = $conex->prepare("SELECT * FROM usuario LEFT JOIN estados ON usuario.id_estados = estados.id_estados WHERE id_rol = 3 ORDER BY documento LIMIT $empieza, $por_pagina");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

// Llamar a la función para generar el archivo Excel
$archivo_excel = generarExcel($resultado);

// Descargar el archivo Excel
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="reporte.xlsx"');
readfile($archivo_excel);
exit();
?>
