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
$sql1 = $conex->prepare("SELECT usuario.*, estados.id_estados, estados.nom_estado FROM usuario LEFT JOIN estados ON usuario.id_estados = estados.id_estados WHERE id_rol = 5 ORDER BY documento LIMIT $empieza, $por_pagina");
$sql1->execute();
$resultado1 = $sql1->fetchAll(PDO::FETCH_ASSOC);

// Contar el número total de registros
$total_registros = $conex->query("SELECT COUNT(*) FROM usuario WHERE id_rol = 3")->fetchColumn();
// Calcular el número total de páginas
$total_paginas = ceil($total_registros / $por_pagina);
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
    <title>Lista de aprendiz</title>
    <link href="../../../img1/logo9.png" rel="icon">
</head>
<body>

<div style="position: fixed; top: 20px; left: 20px;">
    <a class="btn btn-success" href="../index.php">
        <i class="bi bi-chevron-left" style="padding:10px 14px 10px 10px; color:#fff; font-size:15px; background-color:#29CA8E; border-radius:10px;">REGRESAR</i>
    </a>
</div>

<div class="container mt-5">
    <div class="d-flex justify-content-end mb-3">
        <a class="btn btn-success me-2" href="exportar_instructoresexe.php">
            <i class="bi bi-file-earmark-excel" style="padding:10px; border-radius:10px; color:#fff; font-size:15px;">EXCEL</i>
        </a>
        <a class="btn btn-danger" href="exportar_instructores.php">
            <i class="bi bi-printer" style="padding:10px; border-radius:10px; color:#fff; font-size:15px;">PDF</i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Documento</th>
                    <th>Codigo de barras</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th colspan="2" class="text-center">Cambiar estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require '../../../vendor/autoload.php';
                use Picqer\Barcode\BarcodeGeneratorPNG;

                foreach ($resultado1 as $usu) {
                    // Crea una instancia del generador de códigos de barras
                    $generator = new BarcodeGeneratorPNG();
                    // Genera el código de barras utilizando el valor del campo 'codigo_barras' en el bucle actual
                    $codigo_imagen = $generator->getBarcode($usu['codigo_barras'], $generator::TYPE_CODE_128);

                    // Muestra la imagen del código de barras
                    echo "<tr>";
                    echo "<td>" . $usu['documento'] . "</td>";
                    echo "<td><img src='data:image/png;base64," . base64_encode($codigo_imagen) . "' alt='Código de barras'></td>";
                    echo "<td>" . $usu['nombres'] . "</td>";
                    echo "<td>" . $usu['correo'] . "</td>";
                    echo "<td>" . $usu['nom_estado'] . "</td>";
                    echo "<td>
                            <form method='post' action='estado/estado.php'>
                                <input type='hidden' name='actiapre' value='" . $usu['documento'] . "'>
                                <button type='submit' class='btn btn-success btn-sm'>Activo</button>
                            </form>
                        </td>";
                    echo "<td>
                            <form method='post' action='estado/estado.php'>
                                <input type='hidden' name='inaapre' value='" . $usu['documento'] . "'>
                                <button type='submit' class='btn btn-danger btn-sm'>Inactivo</button>
                            </form>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                <?php if ($pagina > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="aprendiz.php?pagina=1"><i class="bi bi-chevron-double-left"></i></a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="aprendiz.php?pagina=<?= $pagina - 1 ?>"><i class="bi bi-chevron-left"></i></a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= ($pagina == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="aprendiz.php?pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($pagina < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="aprendiz.php?pagina=<?= $pagina + 1 ?>"><i class="bi bi-chevron-right"></i></a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="aprendiz.php?pagina=<?= $total_paginas ?>"><i class="bi bi-chevron-double-right"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
