<?php
require_once("../../../db/conexion.php");
require_once("../../../vendor/autoload.php");
use Dompdf\Dompdf;

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

// Crear una nueva instancia de Dompdf
$dompdf = new Dompdf();

// Crear el contenido HTML
$html = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reporte de Entradas</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<h2>Reporte de Entradas</h2>
<table class="table">
<thead>
<tr>
<th>Documento</th>
<th>Nombre</th>
<th>Tipo de Entrada</th>
<th>Fecha de Ingreso</th>
<th>Placa</th>
<th>Serial</th>
</tr>
</thead>
<tbody>';

foreach ($resultado1 as $usu) {
    $html .= '<tr>
    <td>' . $usu['documento'] . '</td>
    <td>' . $usu['nombres'] . '</td>
    <td>' . $usu['nom_tipo'] . '</td>
    <td>' . $usu['entrada_fecha_hora'] . '</td>
    <td>' . (!empty($usu['id_placa']) ? $usu['id_placa'] : 'No') . '</td>
    <td>' . (!empty($usu['serial']) ? $usu['serial'] : 'No') . '</td>
    </tr>';
}

$html .= '</tbody>
</table>
</div>
</body>
</html>';

// Cargar el contenido HTML en Dompdf
$dompdf->loadHtml($html);

// Renderizar el PDF
$dompdf->render();

// Descargar el PDF
$dompdf->stream('reporte_entradas.pdf', ['Attachment' => false]);
exit();
?>
