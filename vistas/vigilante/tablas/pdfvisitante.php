<?php
require_once("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();
require_once("../../../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Función para generar el archivo PDF
function generarPDF($resultado)
{
    // Configuración de DomPdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    // Crear una instancia de Dompdf
    $dompdf = new Dompdf($options);

    // Generar el contenido HTML del PDF
    $html = "<html>
    <head>
        <title>Reporte PDF</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h1>Lista de visitantes</h1>
        <table>
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($resultado as $row) {
        $html .= "<tr>";
        $html .= "<td>{$row['documento']}</td>";
        $html .= "<td>{$row['nombres']}</td>";
        $html .= "<td>{$row['correo']}</td>";
        $html .= "<td>{$row['nom_estado']}</td>";
        $html .= "</tr>";
    }
    $html .= "</tbody></table></body></html>";

    // Cargar el contenido HTML en DomPdf
    $dompdf->loadHtml($html);

    // Renderizar el PDF
    $dompdf->render();

    // Descargar el PDF
    $dompdf->stream('reporte.pdf', ['Attachment' => true]);
}

// Obtener los datos de los visitantes
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

// Llamar a la función para generar el archivo PDF
generarPDF($resultado);
?>
