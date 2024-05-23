<?php
// Incluir el archivo de conexión a la base de datos
require_once("../../db/conexion.php");
require_once("../../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Establecer la conexión a la base de datos
$db = new Database();
$conexion = $db->conectar();

// Función para generar el reporte en formato PDF
function generarPDF($conexion, $documento)
{
    // Configurar opciones para Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

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
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
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
                    <th>Documento</th>
                    <th>Nombres</th>
                    <th>Correo</th>
                    <th>NIT Empresa</th>
                    <th>Contraseña</th>
                    <th>Código</th>
                    <th>Código de Barras</th>
                    <th>ID Rol</th>
                    <th>ID Tipo de Documento</th>
                    <th>ID Estados</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>";

    // Obtener datos del usuario desde la base de datos
    $sql = $conexion->prepare("SELECT * FROM usuario WHERE documento = ?");
    $sql->execute([$documento]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);

    // Agregar datos del usuario a la tabla
    if ($resultado) {
        $html .= "<tr>
                    <td>{$resultado['documento']}</td>
                    <td>{$resultado['nombres']}</td>
                    <td>{$resultado['correo']}</td>
                    <td>{$resultado['nit_empresa']}</td>
                    <td>{$resultado['contrasena']}</td>
                    <td>{$resultado['codigo']}</td>
                    <td>{$resultado['codigo_barras']}</td>
                    <td>{$resultado['id_rol']}</td>
                    <td>{$resultado['id_tipo_documento']}</td>
                    <td>{$resultado['id_estados']}</td>
                    <td>{$resultado['foto']}</td>
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
