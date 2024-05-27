<?php
// Incluir el archivo de conexión a la base de datos
require_once("../../db/conexion.php");
require_once("../../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Establecer la conexión a la base de datos
$db = new Database();
$conexion = $db->conectar();

// Función para obtener el nombre del rol por su identificador
function obtenerNombreRol($conexion, $id_rol) {
    $sql = $conexion->prepare("SELECT nom_rol FROM rol WHERE id_rol = ?");
    $sql->execute([$id_rol]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['nom_rol'] : '';
}

// Función para obtener el nombre del tipo de documento por su identificador
function obtenerNombreTipoDocumento($conexion, $id_tipo_documento) {
    $sql = $conexion->prepare("SELECT nom_doc FROM tipo_documento WHERE id_tipo_documento = ?");
    $sql->execute([$id_tipo_documento]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['nom_doc'] : '';
}

// Función para generar el reporte en formato PDF
function generarPDF($conexion, $documento)
{
    // Configurar opciones para Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    // Establecer orientación horizontal
    $options->set('defaultPaperSize', 'landscape');

    // Inicializar Dompdf con las opciones configuradas
    $dompdf = new Dompdf($options);

    // Contenido HTML para el reporte
    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Reporte PDF de Usuario</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            h1 {
                text-align: center;
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
                font-size: 10px; /* Tamaño de fuente ajustado */
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h1>Reporte PDF de Usuario</h1>
        <table>
            <thead>
                <tr>
                    <th style='width: 8%'>Documento</th>
                    <th style='width: 10%'>Nombres</th>
                    <th style='width: 10%'>Correo</th>
                    <th style='width: 8%'>NIT Empresa</th>
                    <th style='width: 6%'>Código</th>
                    <th style='width: 8%'>Código de Barras</th>
                    <th style='width: 8%'>Rol</th>
                    <th style='width: 8%'>Tipo de Documento</th>
                    <th style='width: 10%'>Foto</th>
                    <th style='width: 8%'>Fecha Entrada</th>
                    <th style='width: 8%'>Fecha Salida</th>
                </tr>
            </thead>
            <tbody>";

    // Obtener datos del usuario desde la base de datos
    $sql = $conexion->prepare("SELECT u.*, es.entrada_fecha_hora, es.salida_fecha_hora
                               FROM usuario u
                               LEFT JOIN entrada_salidas es ON u.documento = es.documento
                               WHERE u.documento = ?");
    $sql->execute([$documento]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);

    // Agregar datos del usuario a la tabla
    if ($resultado) {
        $nombre_rol = obtenerNombreRol($conexion, $resultado['id_rol']);
        $nombre_tipo_documento = obtenerNombreTipoDocumento($conexion, $resultado['id_tipo_documento']);

        $html .= "<tr>
                    <td>{$resultado['documento']}</td>
                    <td>{$resultado['nombres']}</td>
                    <td>{$resultado['correo']}</td>
                    <td>{$resultado['nit_empresa']}</td>
                    <td>{$resultado['codigo']}</td>
                    <td>{$resultado['codigo_barras']}</td>
                    <td>{$nombre_rol}</td>
                    <td>{$nombre_tipo_documento}</td>
                    <td>{$resultado['foto']}</td>
                    <td>{$resultado['entrada_fecha_hora']}</td>
                    <td>{$resultado['salida_fecha_hora']}</td>
                </tr>";
    } else {
        $html .= "<tr><td colspan='11'>No se encontraron datos para el documento proporcionado.</td></tr>";
    }

    $html .= "</tbody>
        </table>
    </body>
    </html>";

    // Cargar contenido HTML en Dompdf
    $dompdf->loadHtml($html);

    // Renderizar PDF
    $dompdf->render();

    // Generar descarga del PDF
    $dompdf->stream('reporte_usuario.pdf');
}

// Iniciar sesión si aún no se ha iniciado
session_start();

// Obtener documento del usuario si está conectado
if (isset($_SESSION['documento'])) {
    $documento = $_SESSION['documento'];
    generarPDF($conexion, $documento);
} else {
    echo 'No se ha iniciado sesión';
}
?>

