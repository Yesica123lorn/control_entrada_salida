<?php
require_once ("../../../db/conexion.php");
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
    LEFT JOIN tipo_entrada ON entrada_salidas.tipo_entrada = tipo_entrada.id_tipo_entrada where entrada_salidas.estado = 2
    ORDER BY entrada_salidas.documento 
    LIMIT $empieza, $por_pagina");

$sql1->execute();
$resultado1 = $sql1->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$sql = $conex->prepare("SELECT COUNT(*) FROM entrada_salidas  ORDER BY documento");
$sql->execute();
$resul = $sql->fetchColumn();
$total_paginas = ceil($resul / $por_pagina);
if ($total_paginas == 0) {
    echo "<center>" . 'Lista Vacia' . "</center>";
} else {
    echo "<center><a href='t_cliente.php?pagina=1'>" . "<i class='fa fa-arrow-left'></i>" . "</a>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/stiledi.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <title>Lista de salida</title>
    <link href="../../../img1/logo9.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        .left-aligned {
            position: fixed;
            top: 3%;
            left: 0;
            margin-left: 1rem; /* Puedes ajustar el margen si lo necesitas */
        }
    </style>
</head>

<body>
    <a class="btn btn left-aligned" href="../index.php">
        <i class="bi bi-chevron-left" style="padding:10px 14px 10px 10px; color:#fff; font-size:15px; background-color:#0d6efd; border-radius:10px;">
            REGRESAR
        </i>
    </a>
    <br>
    <a class="btn btn" href="../excel/excel_clientes.php" style="margin-left: 90%; margin-top:0%;">
        <i class="bi bi-file-earmark-excel" style="padding:10px 10px 10px 10px; border-radius:10px; color:#fff; font-size:15px; background-color:#198754;">
            EXCEL
        </i>
    </a>
    <br>
    <a class="btn btn" href="../reporte/repor_cliente.php" style="margin-left: 88%; margin-top:1%;">
        <i class="bi bi-printer" style="padding:10px 16px 10px 16px; border-radius:10px; color:#fff; font-size:15px; background-color:#E00000;">
            IMPRIMIR
        </i>
    </a>
    <div class="table-responsive">
        <table class="table">
            <!-- El tr nos sirve para crear las filas -->
            <!-- El th se crea la cabecera -->
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Tipo de salida</th>
                    <th>Fecha de Ingreso</th>
                    <th>Placa</th>
                    <th>Serial</th>
                </tr>
            </thead>

            <?php foreach ($resultado1 as $usu) { ?>
                <!-- El td sirve para crear las columnas -->
                <!-- En cada td se va a mostrar los datos de una tabla usando variables -->
                <tr>
                    <td><?= $usu['documento'] ?></td>
                    <td><?= $usu['nombres'] ?></td>
                    <td><?= $usu['nom_tipo'] ?></td>
                    <td><?= $usu['salida_fecha_hora'] ?></td>
                    <td><?= !empty($usu['id_placa']) ? $usu['id_placa'] : 'No' ?></td>
                    <td><?= !empty($usu['serial']) ? $usu['serial'] : 'No' ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="text-center" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group me-2" role="group" aria-label="First group" aling>
            <?php
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<a class='btn btn-primary' href='t_cliente.php?pagina=" . $i . "'> " . $i . " </a>";
            }
            echo "<a href='t_cliente.php?pagina=$total_paginas'>" . "<i class='fa fa-arrow-right'></i>" . "</a></center>";
            ?>
        </div>
    </div>
</body>

</html>
