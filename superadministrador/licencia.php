<?php
require_once("../db/conexion.php");
$db = new Database();
$conectar = $db->conectar();
session_start();
$lista = $conectar->prepare("SELECT * FROM licencias,empresas,estados WHERE licencias.nit_empresa=empresas.nit_empresa AND licencias.id_estado=estados.id_estados ");
$lista->execute();
$listas = $lista->fetchAll(PDO::FETCH_ASSOC);

$activo = true; // Si está activo
// O
$activo = false; // Si está inactivo


if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header('Location: ../../index.html');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Licencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="../../ingresar/css/estyle.css">
    <style>
        .navbar {
            background-color: #343a40;
        }

        .navbar-nav .nav-link {
            color: #fff;
        }

        .navbar-nav .nav-link.active {
            color: #29CA8E;
        }

        .btn-light {
            color: #000;
            background-color: #fff;
        }

        .btn-light:hover {
            color: #fff;
            background-color: #000;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 550px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .table-container {
            margin: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Licencia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="crear.php">Crear Empresa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./crearlicencia.php">Asignacion de Licencia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registro_adm.php">Registro Administrador</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="licencia.php">Licencia</a>
                    </li>
                </ul>
                <form method="POST" action="">
                    <span class="ms-2">
                        <input class="btn btn-outline-danger my-2 my-sm-0" type="submit" value="Cerrar sesión" id="btn_quote" name="cerrar_sesion" />
                    </span>
                </form>
            </div>
        </div>
    </nav>
    
     <div class="modal" id="modal">
        <div class="modal-content">
            <h2 class="text-center mb-4">Panel Administrador - Digite Su Contraseña:</h2>
            <div class="form-group">
                <input type="password" id="passwordInput" class="form-control" placeholder="Contraseña">
            </div>
            <button onclick="validarCodigo()" class="btn btn-success">Aceptar</button>
        </div>
    </div>

<body>
    <div class="container">
        <!--<div class="row mt-3">-->
        <!--    <div class="col">-->
        <!--        <a href="crearlicencia.php" class="btn btn-primary">Crear una licencia</a>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="row mt-3">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Licencia</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listas as $lista) { 
                            $estadoClase = '';
                            $color = '';
                            $mensaje = '';
                            $botonInactivo = '';
                            $botonCancelar = '';
                            $activo = '';

                            // Comprobar si la hora de finalización ha pasado
                            $horaFinalizacionPasada = strtotime($lista["fecha_fin"]) < strtotime("now");

                            // Si la licencia está activa y la hora de finalización ha pasado
                            if ($lista["licencia"] == 'activo' && $horaFinalizacionPasada) {
                            // Actualizar el estado de la licencia en la base de datos a 'inactivo'
                            $updateEstado = $conn->prepare("UPDATE licencias SET licencia = 'inactivo' WHERE licencia = :licencia");
                            $updateEstado->bindParam(':licencia', $lista["licencia"], PDO::PARAM_INT);
                            $updateEstado->execute();

                            // Actualizar las variables para reflejar el nuevo estado
                            $estadoClase = 'table-warning';
                            $botonInactivo = 'disabled';
                            $color = 'orange';
                            $mensaje = 'Bloqueado';
                            } elseif ($lista["licencia"] == 'inactivo' && !$horaFinalizacionPasada) {
                            // Si la licencia está inactiva y la hora de finalización no ha pasado
                            // Actualizar el estado de la licencia en la base de datos a 'activo'
                            $updateEstado = $conn->prepare("UPDATE licencias SET licencia = 'activo' WHERE licencia = :licencia");
                            $updateEstado->bindParam(':licencia', $lista["licencia"], PDO::PARAM_INT);
                            $updateEstado->execute();

                            // Actualizar las variables para reflejar el nuevo estado
                            $estadoClase = 'table-success';
                            $activo = 'disabled';
                            $color = 'green';
                            $mensaje = 'Disponible';

                                } elseif ($lista["licencia"] == 'inactivo' && $horaFinalizacionPasada) {
                            // Si la licencia está inactiva y la hora de finalización ha pasado
                            // No es necesario hacer nada aquí, simplemente mantener el estado inactivo
                                $color = 'orange';
                                $mensaje = 'Inactivo';
                            }
                            {
                            // Actualiza las variables para reflejar el nuevo estado
                            $estadoClase = 'table-success';
                            $activo = 'activo';
                            $color = 'green';
                            $mensaje = 'Disponible';
                            }
        
                            if ($lista["licencia"] == 'inactivo') {
                            $estadoClase = '';
                            $botonInactivo = 'disabled';
                            $color = 'orange';
                            $mensaje = 'Esta inactivo';
                            } elseif ($lista["licencia"] == 'cancelado') {
                            $estadoClase = '';
                            $botonCancelar = 'disabled';
                            $color = 'red';
                            $mensaje = 'Esta cancelado';
                            } elseif ($lista["licencia"] == 'activo') {
                            $estadoClase = '';
                            $activo = 'disabled';
                            $color = 'green';
                            $mensaje = 'activo';
                            }
                        ?> 
                            <tr>
                                <td><?= $lista["licencia"] ?></td>
                                <td><?= $lista["nombre"] ?></td>
                                <td><?= $lista["telefono"] ?></td>
                                <td><?= $lista["fecha"] ?></td>
                                <td><?= $lista["fecha_fin"] ?></td>
                                <td><?= $lista["nom_estado"] ?></td>
                                <td>
                                    <form method="GET" action="renovar.php">
                                        <input type="hidden" name="nit_empresa" value="<?= $usu["nit_empresa"] ?>">
                                        <button class="btn btn-success" type="submit" name="acti" <?= $activo ?>>Renovar</button>
                                    </form>
                                </td>
                                <!--<td>-->
                                <!--    <a href="activar_licencia.php?id=<?= $lista["licencia"] ?>" class="btn btn-success">Activar</a>-->
                                <!--    <a href="desactivar_licencia.php?id=<?= $lista["licencia"] ?>" class="btn btn-danger">Desactivar</a>-->
                                <!--</td>-->
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <!-- BotÃģn de Cerrar SesiÃģn -->
                <form action="../index.html" method="post">
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+7HAuoDl5QaVVt72hYx0K5L7B4jBi" crossorigin="anonymous"></script>

    <script>
        function closeModal() {
            document.getElementById("modal").style.display = "none";
            window.location.href = "./../index.php";
        }

        function validarCodigo() {
            const codigoCorrecto = "yesicagomezrueda";
            const codigoIngresado = document.getElementById("passwordInput").value;

            if (codigoIngresado === codigoCorrecto) {
                alert("Bienvenido al panel de administrador.");
                document.getElementById("modal").style.display = "none";
            } else {
                alert("Contraseña incorrecta.");
            }
        }

        window.onload = function() {
            document.getElementById("modal").style.display = "block";
        };
    </script>

</body>

</html>