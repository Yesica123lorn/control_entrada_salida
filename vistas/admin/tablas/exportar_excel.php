<?php
require_once("../../../db/conexion.php");
require_once("../../../vendor/autoload.php"); // Asegúrate de proporcionar la ruta correcta al archivo autoload.php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$daba = new Database();
$conex = $daba->conectar();

// PAGINACION
$por_pagina = 5;
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}
$empieza = ($pagina - 1) * $por_pagina;
$sql1 = $conex->prepare("SELECT * FROM entrada_salidas 
    LEFT JOIN usuario ON entrada_salidas.documento = usuario.documento 
    LEFT JOIN tipo_entrada ON entrada_salidas.tipo_entrada = tipo_entrada.id_tipo_entrada where entrada_salidas.estado = 1
    ORDER BY entrada_salidas.documento 
    LIMIT $empieza, $por_pagina");
$sql1->execute();
$resultado1 = $sql1->fetchAll(PDO::FETCH_ASSOC);

// Crear un nuevo objeto PhpSpreadsheet
$spreadsheet = new Spreadsheet();
$hoja = $spreadsheet->getActiveSheet();
$hoja->setTitle("Entradas");

// Escribir los encabezados de la tabla
$hoja->setCellValue('A1', 'Documento');
$hoja->setCellValue('B1', 'Nombre');
$hoja->setCellValue('C1', 'Tipo de Entrada');
$hoja->setCellValue('D1', 'Fecha de Ingreso');
$hoja->setCellValue('E1', 'Placa');
$hoja->setCellValue('F1', 'Serial');

// Escribir los datos de la tabla en la hoja de trabajo
$indiceFila = 2; // Comenzar desde la fila 2
foreach ($resultado1 as $usu) {
    $hoja->setCellValue('A' . $indiceFila, $usu['documento']);
    $hoja->setCellValue('B' . $indiceFila, $usu['nombres']);
    $hoja->setCellValue('C' . $indiceFila, $usu['nom_tipo']);
    $hoja->setCellValue('D' . $indiceFila, $usu['entrada_fecha_hora']);
    $hoja->setCellValue('E' . $indiceFila, !empty($usu['id_placa']) ? $usu['id_placa'] : 'No');
    $hoja->setCellValue('F' . $indiceFila, !empty($usu['serial']) ? $usu['serial'] : 'No');
    $indiceFila++;
}

// Establecer anchos de columna
$hoja->getColumnDimension('A')->setWidth(15);
$hoja->getColumnDimension('B')->setWidth(25);
$hoja->getColumnDimension('C')->setWidth(20);
$hoja->getColumnDimension('D')->setWidth(25);
$hoja->getColumnDimension('E')->setWidth(15);
$hoja->getColumnDimension('F')->setWidth(15);

// Encabezado de contenido para descarga de archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_entradas.xlsx"');
header('Cache-Control: max-age=0');

// Escribir el archivo Excel en la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>
