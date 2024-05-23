<?php
// Incluir el archivo de conexión a la base de datos
require_once("../../db/conexion.php");
require_once("../../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Establecer la conexión a la base de datos
$db = new Database();
$conexion = $db->conectar();

function generarExcel($conexion, $userData)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Column headers
    $sheet->setCellValue('A1', 'Documento');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Correo');
    $sheet->setCellValue('D1', 'NIT Empresa');
    $sheet->setCellValue('E1', 'Contraseña');
    $sheet->setCellValue('F1', 'Código');
    $sheet->setCellValue('G1', 'Código de Barras');
    $sheet->setCellValue('H1', 'ID Rol');
    $sheet->setCellValue('I1', 'ID Tipo de Documento');
    $sheet->setCellValue('J1', 'ID Estados');
    $sheet->setCellValue('K1', 'Foto');

    // Fill data for the current user
    $row = 2;
    $sheet->setCellValue('A' . $row, $userData['documento']);
    $sheet->setCellValue('B' . $row, $userData['nombres']);
    $sheet->setCellValue('C' . $row, $userData['correo']);
    $sheet->setCellValue('D' . $row, $userData['nit_empresa']);
    $sheet->setCellValue('E' . $row, $userData['contrasena']);
    $sheet->setCellValue('F' . $row, $userData['codigo']);
    $sheet->setCellValue('G' . $row, $userData['codigo_barras']);
    $sheet->setCellValue('H' . $row, $userData['id_rol']);
    $sheet->setCellValue('I' . $row, $userData['id_tipo_documento']);
    $sheet->setCellValue('J' . $row, $userData['id_estados']);
    $sheet->setCellValue('K' . $row, $userData['foto']);

    // Save Excel file
    $writer = new Xlsx($spreadsheet);
    $filename = 'reporte_usuario_' . $userData['documento'] . '.xlsx'; // Use the user's document as part of the filename
    $writer->save($filename);

    return $filename;
}

// Iniciar sesión si aún no se ha iniciado
session_start();

// Obtener datos del usuario si está conectado
if (isset($_SESSION['documento'])) {
    $documento = $_SESSION['documento'];
    try {
        // Obtener datos del usuario actualmente conectado
        $sql = $conexion->prepare("SELECT * FROM usuario WHERE documento = ?");
        $sql->execute([$documento]);
        $userData = $sql->fetch(PDO::FETCH_ASSOC);

        // Generar el archivo Excel solo para este usuario
        $archivo_excel = generarExcel($conexion, $userData);

        // Descargar el archivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $archivo_excel . '"');
        header('Cache-Control: max-age=0');
        readfile($archivo_excel);
        exit();
    } catch (PDOException $e) {
        echo 'Error al obtener los datos del usuario: ' . $e->getMessage();
    }
} else {
    echo 'No se ha iniciado sesión';
}
?>
