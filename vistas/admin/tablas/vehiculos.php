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
$sql1 = $conex->prepare("SELECT * FROM vehiculos 
                         LEFT JOIN marcas ON vehiculos.id_marca = marcas.id_marca
                         LEFT JOIN color ON vehiculos.id_color = color.id_color 
                         LEFT JOIN tipo_vehiculo ON vehiculos.id_tipo_vehiculo = tipo_vehiculo.id_tipo_vehiculo 
                         LEFT JOIN estados ON vehiculos.estado = estados.id_estados
                         ORDER BY documento LIMIT $empieza, $por_pagina");
$sql1->execute();
$resultado1 = $sql1->fetchAll(PDO::FETCH_ASSOC);

$sql = $conex->prepare("SELECT COUNT(*) FROM vehiculos ORDER BY documento");
$sql->execute();
$resul = $sql->fetchColumn();
$total_paginas = ceil($resul / $por_pagina);
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
    <title>Lista de vehiculos</title>
    <link href="../../../img1/logo9.png" rel="icon">
</head>
<body class="bg-light">

<a class="btn btn-primary" href="../index.php" style="margin-left: 3%; margin-top:3%; position:absolute;">
    <i class="bi bi-chevron-left" style="padding:10px 14px 10px 10px; color:#fff; font-size:15px; background-color:#0d6efd; border-radius:10px;">
        REGRESAR
    </i>
</a>

<div class="container mt-4">
    <div class="d-flex justify-content-end mb-3">
        <a class="btn btn-success me-2" href="excelvehiculo.php">
            <i class="bi bi-file-earmark-excel" style="padding:10px 10px 10px 10px; border-radius:10px; color:#fff; font-size:15px; background-color:#198754;">EXCEL</i>
        </a>
        <a class="btn btn-danger" href="pdfvehiculo.php">
            <i class="bi bi-printer" style="padding:10px 16px 10px 16px; border-radius:10px; color:#fff; font-size:15px; background-color:#E00000;">
                IMPRIMIR
            </i>
        </a>
    </div>

    <?php if ($total_paginas == 0): ?>
        <div class="alert alert-info text-center">Lista Vacia</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Documento</th>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Tipo De Vehiculo</th>
                        <th>Estado</th>
                        <th colspan="2" class="text-center">Cambiar estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado1 as $usu): ?>
                        <tr>
                            <td><?= $usu['documento'] ?></td>
                            <td><?= $usu['id_placa'] ?></td>
                            <td><?= $usu['nom_marca'] ?></td>
                            <td><?= $usu['nom_color'] ?></td>
                            <td><?= $usu['nom_vehiculo'] ?></td>
                            <td><?= $usu['nom_estado'] ?></td>
                            <td>
                                <form method="post" action="estado/estado.php">
                                    <input type="hidden" name="actiplaca" value="<?= $usu['id_placa'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Activo</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="estado/estado.php">
                                    <input type="hidden" name="inaplaca" value="<?= $usu['id_placa'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Inactivo</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            <nav>
                <ul class="pagination">
                    <li class="page-item <?= ($pagina == 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="t_cliente.php?pagina=1"><i class="fa fa-arrow-left"></i></a>
                    </li>
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= ($pagina == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="t_cliente.php?pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($pagina == $total_paginas) ? 'disabled' : '' ?>">
                        <a class="page-link" href="t_cliente.php?pagina=<?= $total_paginas ?>"><i class="fa fa-arrow-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
