<?php
require_once ("db/conexion.php");
$base = new Database();
$conexion = $base->conectar();

require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

$control2 = $conexion->prepare("SELECT * From rol LIMIT 2, 7");
$control2->execute();
$query2 = $control2->fetch();

$control6 = $conexion->prepare("SELECT * From empresas where nit_empresa >= 1");
$control6->execute();
$query6 = $control6->fetch();

if (isset($_POST["btn-guardar"])) {
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $correo = $_POST['correo'];
    $raw_password = $_POST['contrasena'];
    $nit_empresa = $_POST['nit_empresa'];  // Contraseña sin cifrar


    $codigo_de_barras = uniqid();

    $generator = new BarcodeGeneratorPNG();
    $codigo_imagen = $generator->getBarcode($codigo_de_barras, $generator::TYPE_CODE_128);

    // Guardar el código de barras en un archivo
    file_put_contents(__DIR__ . '/images/' . $codigo_de_barras . '.png', $codigo_imagen);

    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

    $rol = $_POST['rol'];
    $id_tipo_documento = $_POST['id_tipo_documento'];

    // Puedes definir el valor de id_estados directamente o recuperarlo de alguna lógica específica
    $id_estados = 1; // Por ejemplo, asumamos que 1 es un estado válido

    // Utilizamos consultas preparadas para mejorar la seguridad
    $validar = $conexion->prepare("SELECT * FROM usuario WHERE documento = ?");
    $validar->execute([$documento]);
    $fila1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($fila1) {
        echo '<script>alert("El documento ya está registrado.");</script>';
    } else {
        // Utilizamos consultas preparadas para mejorar la seguridad
        $consulta3 = $conexion->prepare("INSERT INTO usuario (documento, nombres, correo, contrasena,codigo_barras, id_rol, id_tipo_documento, id_estados ,nit_empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $consulta3->execute([$documento, $nombres, $correo, $hashed_password, $codigo_de_barras, $rol, $id_tipo_documento, $id_estados, $nit_empresa]);
        ;
        echo '<script>alert ("Registro exitoso,gracias");</script>';
        echo '<script> window.location= "login.php"</script>';
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            /* Fondo claro */
            color: #495057;
            /* Color del texto */
            padding-top: 50px;
        }

        .form-container {
            background-color: #ffffff;
            /* Fondo blanco */
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            /* Sombra ligera */
        }

        select,
        input {
            border: 2px solid #007bff;
            /* Color del borde */
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            color: #495057;
            /* Color del texto del input */
            background-color: #fff;
            /* Color del fondo del input */
        }

        label {
            margin-bottom: 0;
        }

        .btn-primary {
            background-color: #007bff;
            /* Color del botón principal */
            border-color: #007bff;
        }
    </style>
    <script>
        function validateInput(event) {
            const input = event.target;
            if (input.name === 'documento') {
                input.value = input.value.replace(/[^0-9]/g, '').slice(0, 11);
            } else if (input.name === 'nombres') {
                input.value = input.value.replace(/[^a-zA-Z\s]/g, '').slice(0, 30);
            }
        }
    </script>
</head>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container">
                <form method="POST" action="">
                    <h2 class="mb-4">Registro de Usuario</h2>
                    <div class="form-group">
                        <label for="id_tipo_documento">Tipo de Documento:</label>
                        <select class="form-control" name="id_tipo_documento" required>
                            <option value="">Seleccionar Tipo de Documento</option>
                            <option value="1">CC</option>
                            <option value="2">TI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="documento">Documento de Identidad:</label>
                        <input type="text" class="form-control" name="documento" required pattern="\d{1,11}" maxlength="11" oninput="validateInput(event)">
                    </div>
                    <div class="form-group">
                        <label for="nombres">Nombre:</label>
                        <input type="text" class="form-control" name="nombres" required pattern="[a-zA-Z\s]{1,30}" maxlength="30" oninput="validateInput(event)">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Electrónico:</label>
                        <input type="email" class="form-control" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" class="form-control" name="contrasena" required minlength="8">
                    </div>
                    <div class="col-sm-15 mb-3 mb-sm-2">
                        <label>Rol</label>
                        <select name="rol" class="form-control form-control-user" id="exampleFirstName" required>
                            <option value="">Elegir</option>
                            <?php
                            do {
                            ?>
                                <option value="<?php echo ($query2['id_rol']) ?>">
                                    <?php echo ($query2['nom_rol']) ?>
                                </option>
                            <?php
                            } while ($query2 = $control2->fetch());
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-15 mb-3 mb-sm-2">
                        <label>Nit Empresa</label>
                        <select name="nit_empresa" class="form-control form-control-user" id="exampleFirstName" required style="width: 100%;">
                            <option value="">Elegir</option>
                            <?php
                            if ($query6) { // Verificar si hay resultados en $query6
                                do {
                            ?>
                                    <option value="<?php echo ($query6['nit_empresa']) ?>">
                                        <?php echo ($query6['nombre']) ?>
                                    </option>
                            <?php
                                } while ($query6 = $control6->fetch());
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="terminos_condiciones" required>
                        <label class="form-check-label" for="terminos_condiciones">Acepto los Términos y Condiciones</label>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn-guardar">Guardar</button>
                    <p class="mt-3">¿Ya tienes una cuenta? <a class="ingresar" href="login.php">Ingresar</a></p>
                    <a href="index.html" class="btn btn-dark">Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>

</html>
