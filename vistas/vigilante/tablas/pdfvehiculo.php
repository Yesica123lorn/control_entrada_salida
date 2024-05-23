<?php
require_once("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();
require_once("../../../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Función para generar el reporte en formato PDF
function generarPDF($resultado)
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
        <title>Reporte PDF de Vehículos</title>
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
        <h1>Reporte PDF de Vehículos</h1>
        <table>
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Color</th>
                    <th>Tipo de Vehiculo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>";

    // Agregar datos de los vehículos a la tabla
    foreach ($resultado as $row_data) {
        $html .= "<tr>
                    <td>{$row_data['documento']}</td>
                    <td>{$row_data['id_placa']}</td>
                    <td>{$row_data['nom_marca']}</td>
                    <td>{$row_data['nom_color']}</td>
                    <td>{$row_data['nom_vehiculo']}</td>
                    <td>{$row_data['nom_estado']}</td>
                </tr>";
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
    $dompdf->stream('reporte_vehiculos.pdf');
}

// Obtener datos de los vehículos desde la base de datos
$por_pagina = 5;
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}
$empieza = ($pagina - 1) * $por_pagina;
$sql1 = $conex->prepare("SELECT * FROM vehiculos 
                         LEFT JOIN marcas ON vehiculos.id_marca = marcas.id_marca
                         LEFT JOIN color ON vehiculos.id_color = color.id_color 
                         LEFT JOIN tipo_vehiculo ON vehiculos.id_tipo_vehiculo = tipo_vehiculo.id_tipo_vehiculo 
                         LEFT JOIN estados ON vehiculos.estado = estados.id_estados
                         ORDER BY documento LIMIT $empieza, $por_pagina");
$sql1->execute();
$resultado1 = $sql1->fetchAll(PDO::FETCH_ASSOC);

// Generar reporte PDF
generarPDF($resultado1);
?>
