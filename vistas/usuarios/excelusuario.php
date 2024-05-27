<?php
// Incluir el archivo de conexión a la base de datos
require_once("../../db/conexion.php");
require_once("../../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Establecer la conexión a la base de datos
$db = new Database();
$conexion = $db->conectar();

function obtenerNombreRol($conexion, $id_rol) {
    $sql = $conexion->prepare("SELECT nom_rol FROM rol WHERE id_rol = ?");
    $sql->execute([$id_rol]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['nom_rol'] : '';
}

function obtenerNombreTipoDocumento($conexion, $id_tipo_documento) {
    $sql = $conexion->prepare("SELECT nom_doc FROM tipo_documento WHERE id_tipo_documento = ?");
    $sql->execute([$id_tipo_documento]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['nom_doc'] : '';
}

function obtenerNombreEstado($conexion, $id_estado) {
    $sql = $conexion->prepare("SELECT nom_estado FROM estados WHERE id_estados = ?");
    $sql->execute([$id_estado]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['nom_estado'] : '';
}

function generarExcel($conexion, $userData, $entradaSalidas)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Column headers
    $sheet->setCellValue('A1', 'Documento');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Correo');
    $sheet->setCellValue('D1', 'NIT Empresa');
    $sheet->setCellValue('E1', 'Código');
    $sheet->setCellValue('F1', 'Código de Barras');
    $sheet->setCellValue('G1', 'Rol');
    $sheet->setCellValue('H1', 'Tipo de Documento');
    $sheet->setCellValue('I1', 'Estado');
    $sheet->setCellValue('J1', 'Fecha Entrada');
    $sheet->setCellValue('K1', 'Fecha Salida');

    // Fill data for the current user
    $sheet->setCellValue('A2', $userData['documento']);
    $sheet->setCellValue('B2', $userData['nombres']);
    $sheet->setCellValue('C2', $userData['correo']);
    $sheet->setCellValue('D2', $userData['nit_empresa']);
    $sheet->setCellValue('E2', $userData['codigo']);
    $sheet->setCellValue('F2', $userData['codigo_barras']);
    $sheet->setCellValue('G2', obtenerNombreRol($conexion, $userData['id_rol']));
    $sheet->setCellValue('H2', obtenerNombreTipoDocumento($conexion, $userData['id_tipo_documento']));
    $sheet->setCellValue('I2', obtenerNombreEstado($conexion, $userData['id_estados']));

    // Fill entry and exit data
    $row = 2;
    foreach ($entradaSalidas as $entradaSalida) {
        $row++;
        $sheet->setCellValue('J' . $row, $entradaSalida['entrada_fecha_hora']);
        $sheet->setCellValue('K' . $row, $entradaSalida['salida_fecha_hora']);
    }

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

        // Obtener entradas y salidas del usuario
        $sql = $conexion->prepare("SELECT * FROM entrada_salidas WHERE documento = ?");
        $sql->execute([$documento]);
        $entradaSalidas = $sql->fetchAll(PDO::FETCH_ASSOC);

        // Generar el archivo Excel solo para este usuario
        $archivo_excel = generarExcel($conexion, $userData, $entradaSalidas);

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

