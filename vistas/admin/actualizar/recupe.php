<?php
require_once ("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION['documento'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST["recucon"])) {
    $nueva_contra = $_POST['contra'];
    $confirmar_contra = $_POST['confirmar_contra'];

    // Verificar que los campos no estén vacíos
    if (empty($nueva_contra) || empty($confirmar_contra)) {
        echo '<script>alert("EXISTEN DATOS VACIOS");</script>';
        echo '<script>window.location="../index.php"</script>';
        exit();
    }

    // Verificar que la contraseña confirmada coincida con la nueva contraseña
    if ($nueva_contra !== $confirmar_contra) {
        echo '<script>alert("Las contraseñas no coinciden.");</script>';
        echo '<script>window.location="../index.php"</script>';
        exit();
    }

    // Generar hash de la nueva contraseña
    $hashed_password = password_hash($nueva_contra, PASSWORD_BCRYPT, ['cost' => 14]);

    // Actualizar la contraseña en la base de datos
    $update = $conex->prepare("UPDATE usuario SET contrasena = ? WHERE documento = ?");
    $update->execute([$hashed_password, $_SESSION['documento']]);

    echo '<script>alert("SE HA ACTUALIZADO LA CONTRASEÑA CORRECTAMENTE");</script>';
    echo '<script>window.location="../index.php"</script>';
    exit();
} elseif (isset($_POST["recufas"])) {
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>RECUPERAR CONTRASEÑA</title>

        <link href="../../../img/logo_gym.png" rel="icon">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">

    </head>

    <body class="bg-gradient-primary">

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-2">Olvidaste tu contraseña?</h1>
                                        </div>
                                        <form class="user" method="post">
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user" id="documento"
                                                    name="contra" placeholder="Ingrese Nueva Contrasña">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user" id="documento"
                                                    name="confirmar_contra" placeholder="Confirma Contraseña">
                                            </div>
                                            <button type="submit" name="recucon"
                                                class="btn btn-primary btn-user btn-block">INGRESAR</button>
                                        </form>
                                        <div class="text-center">
                                            <a class="small" href="../index.php">REGRESAR</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="../../vendor/jquery/jquery.min.js"></script>
        <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

    </body>

    </html>

    <?php
}
?>