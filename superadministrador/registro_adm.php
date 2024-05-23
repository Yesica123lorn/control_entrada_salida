<?php
require_once("../db/conexion.php");
$base = new Database();
$conexion = $base->conectar();

require './../vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header('Location:../index.html');
    exit();
}

$control2 = $conexion->prepare("SELECT * FROM rol WHERE id_rol = 1");
$control2->execute();
$query2 = $control2->fetchAll(PDO::FETCH_ASSOC);

$control6 = $conexion->prepare("SELECT * FROM licencias");
$control6->execute();
$query6 = $control6->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["btn-guardar"])) {
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $correo = $_POST['correo'];
    $raw_password = $_POST['contrasena'];
    $nit_empresa = $_POST['nit_empresa'];

    $codigo_de_barras = uniqid();

    $generator = new BarcodeGeneratorPNG();
    $codigo_imagen = $generator->getBarcode($codigo_de_barras, $generator::TYPE_CODE_128);

    // Corrección en la ruta del archivo
    file_put_contents(__DIR__ . '/../images/' . $codigo_de_barras . '.png', $codigo_imagen);

    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

    $rol = $_POST['rol'];
    $id_tipo_documento = $_POST['id_tipo_documento'];
    $id_estados = 1; // Asumimos que 1 es un estado válido

    // Validar si el documento ya está registrado
    $validar = $conexion->prepare("SELECT * FROM usuario WHERE documento = ?");
    $validar->execute([$documento]);
    $fila1 = $validar->fetch(PDO::FETCH_ASSOC);

    if ($fila1) {
        echo '<script>alert("El documento ya está registrado.");</script>';
    } else {
        $consulta3 = $conexion->prepare("INSERT INTO usuario (documento, nombres, correo, contrasena, codigo_barras, id_rol, id_tipo_documento, id_estados, nit_empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $consulta3->execute([$documento, $nombres, $correo, $hashed_password, $codigo_de_barras, $rol, $id_tipo_documento, $id_estados, $nit_empresa]);
        echo '<script>alert("Registro exitoso, gracias");</script>';
        echo '<script>window.location= "index.php"</script>';
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .form-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
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
                </ul>
                <form method="POST" action="">
                    <span class="ms-2">
                        <input class="btn btn-outline-danger my-2 my-sm-0" type="submit" value="Cerrar sesión" id="btn_quote" name="cerrar_sesion" />
                    </span>
                </form>
                
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Barra de navegación existente aquí -->
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
    <br><br>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <form method="POST" action="registro_adm.php" onsubmit="return validarFormulario()">
                        <h2 class="mb-4">Registro de Usuario</h2>
                        <div class="form-group">
                            <label for="id_tipo_documento">Tipo de Documento:</label>
                            <select class="form-control" name="id_tipo_documento" required>
                                <option value="">Seleccionar Tipo de Documento</option>
                                <option value="1">CC</option>
                                <option value="2">CE</option>
                            </select>
                        </div>
                        <div class="form-group">
                        <label for="documento">Documento de Identidad:</label>
                        <input type="number" class="form-control" name="documento" required maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                    </div>

                    <div class="form-group">
                        <label for="nombres">Nombre:</label>
                        <input type="text" class="form-control" name="nombres" required maxlength="20" oninput="javascript: this.value = this.value.replace(/[^A-Za-z]/g, '').slice(0, 20);">
                    </div>

                        <div class="form-group">
                            <label for="correo">Correo Electrónico:</label>
                            <input type="email" class="form-control" name="correo" required>
                        </div>
                        <div class="form-group">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" class="form-control" name="contrasena" id="contrasena" required oninput="validarContrasena()">
                        <small id="contrasenaHelp" class="form-text text-muted">Debe contener al menos una mayúscula y tener un mínimo de 8 caracteres.</small>
                         </div>

                        <div class="form-group">
                            <label for="rol">Rol:</label>
                            <select name="rol" class="form-control" required>
                                <option value="">Elegir</option>
                                <?php foreach ($query2 as $rol) : ?>
                                    <option value="<?php echo $rol['id_rol']; ?>"><?php echo $rol['nom_rol']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nit_empresa">Nit Empresa:</label>
                            <select name="nit_empresa" class="form-control" required>
                                <option value="">Elegir</option>
                                <?php foreach ($query6 as $licencia) : ?>
                                    <option value="<?php echo $licencia['nit_empresa']; ?>"><?php echo $licencia['nit_empresa']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="terminos_condiciones" required>
                            <label class="form-check-label" for="terminos_condiciones">Acepto los Términos y Condiciones</label>
                        </div>
                        <button type="submit" class="btn btn-primary" name="btn-guardar">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0sG1M5b4hcpxyD9F7jL+7HAuoDl5QaVVt72hYx0K5L7B4jBi" crossorigin="anonymous"></script>

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

        function validarFormulario() {
            const contrasenaInput = document.getElementById('contrasena');
            const contrasenaValue = contrasenaInput.value;

            // Validar que la contraseña tenga al menos una mayúscula y un mínimo de 8 caracteres
            if (!/[A-Z]/.test(contrasenaValue) || contrasenaValue.length < 8) {
                alert('La contraseña debe contener al menos una mayúscula y tener un mínimo de 8 caracteres.');
                contrasenaInput.focus();
                return false;
            }

            return true;
        }
    </script>
    <script>
    function validarContrasena() {
        const contrasenaInput = document.getElementById('contrasena');
        const contrasenaValue = contrasenaInput.value;

        const tieneMayuscula = /[A-Z]/.test(contrasenaValue);
        const tieneLongitudSuficiente = contrasenaValue.length >= 8;

        if (tieneMayuscula && tieneLongitudSuficiente) {
            contrasenaInput.setCustomValidity('');
        } else {
            contrasenaInput.setCustomValidity('La contraseña debe contener al menos una mayúscula y tener un mínimo de 8 caracteres.');
        }
    }
</script>


</body>

</html>
