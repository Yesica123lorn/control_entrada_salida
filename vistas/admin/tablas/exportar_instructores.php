<?php
require_once ("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();
require_once ("../../../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuración de DomPdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

// Crear una instancia de Dompdf
$dompdf = new Dompdf($options);

// Obtener los datos de la primera tabla
$sql1 = $conex->prepare("SELECT * FROM usuario LEFT JOIN estados ON usuario.id_estados = estados.id_estados WHERE id_rol = 5 ORDER BY documento");
$sql1->execute();
$resultado1 = $sql1->fetchAll(PDO::FETCH_ASSOC);

// Generar el contenido HTML del PDF
$html = "<html>
<head>
    <title>Reporte PDF</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css'>
</head>
<body>
    <h1>Instructores</h1>
    <table class='table'>
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>";
foreach ($resultado1 as $row) {
    $html .= "<tr>";
    $html .= "<td>{$row['documento']}</td>";
    $html .= "<td>{$row['nombres']}</td>";
    $html .= "<td>{$row['correo']}</td>";
    $html .= "<td>{$row['nom_estado']}</td>";
    $html .= "</tr>";
}
$html .= "</tbody></table>";

// Cargar el contenido HTML en DomPdf
$dompdf->loadHtml($html);

// Renderizar el PDF
$dompdf->render();

// Salida del PDF (descargar o mostrar)
$dompdf->stream('reporte.pdf', ['Attachment' => true]);
