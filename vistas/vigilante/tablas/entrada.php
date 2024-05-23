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
    LEFT JOIN tipo_entrada ON entrada_salidas.tipo_entrada = tipo_entrada.id_tipo_entrada where entrada_salidas.estado = 1
    ORDER BY entrada_salidas.documento 
    LIMIT $empieza, $por_pagina");

$sql1->execute();
$resultado1 = $sql1->fetchAll(PDO::FETCH_ASSOC);

$sql = $conex->prepare("SELECT COUNT(*) FROM entrada_salidas  ORDER BY documento");
$sql->execute();
$resul = $sql->fetchColumn();
$total_paginas = ceil($resul / $por_pagina);
if ($total_paginas == 0) {
    echo "<center>" . 'Lista Vacia' . "</center>";
} else {
    echo "<center><a href='t_cliente.php?pagina=1'>" . "<i class='fa fa-arrow-left'></i>" . "</a></center>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/stiledi.css">
    <link rel="stylesheet" href=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <title>Lista de entradas</title>
    <link href="../../../img1/logo9.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>
<body>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <a class="btn btn-primary" href="../index.php">
                    <i class="bi bi-chevron-left"></i> REGRESAR
                </a>
                <a class="btn btn-success float-end" href="exportar_excel.php">
                    <i class="bi bi-file-earmark-excel"></i> EXCEL
                </a>
                <a class="btn btn-danger float-end me-2" href="exportar_pdf.php">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
        </div>

        <!--creamos la tabla-->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered">
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
                <tbody>
                    <?php
                    foreach ($resultado1 as $usu) {
                    ?>
                    <tr>
                        <td><?= $usu['documento'] ?></td>
                        <td><?= $usu['nombres'] ?></td>
                        <td><?= $usu['nom_tipo'] ?></td>
                        <td><?= $usu['entrada_fecha_hora'] ?></td>
                        <td><?= !empty($usu['id_placa']) ? $usu['id_placa'] : 'No' ?></td>
                        <td><?= !empty($usu['serial']) ? $usu['serial'] : 'No' ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <div class="btn-group" role="group" aria-label="Pagination">
                <?php
                for ($i = 1; $i <= $total_paginas; $i++) {
                    echo "<a class='btn btn-primary' href='t_cliente.php?pagina=" . $i . "'> " . $i . " </a>";
                }
                echo "<a class='btn btn-primary' href='t_cliente.php?pagina=$total_paginas'>" . "<i class='fa fa-arrow-right'></i>" . "</a>";
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
