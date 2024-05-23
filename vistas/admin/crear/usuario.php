<?php
require_once ("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();




$control2 = $conex->prepare("SELECT * From rol LIMIT 1, 7");
$control2->execute();
$query2 = $control2->fetch();


$control3 = $conex->prepare("SELECT * From tipo_documento");
$control3->execute();
$query3 = $control3->fetch();


$control6 = $conex->prepare("SELECT * From licencias LIMIT 1");
$control6->execute();
$query6 = $control6->fetch();


if (isset($_POST["validar_V"])) {
    $cedula = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $correo = $_POST['correo'];
    $contra = $_POST['contrasena'];
    $rol = $_POST['rol'];
    $tipo = $_POST['tipo'];
    $nit_empresa = $_POST['nit_empresa'];


        $validar = $conex->prepare("SELECT * FROM usuario where documento='$cedula'");
        $validar->execute();
        $queryi = $validar->fetch();
        $incri = password_hash($contra, PASSWORD_BCRYPT, ['cost' => 14,]);




        if ($cedula == "" || $nombres == "" || $correo == "" || $contra == "" || $rol == "" || $tipo == "") {


            echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
            echo '<script>window.location="./usuario.php"</script>';
        } else if ($queryi) {
            echo '<script>alert ("DOCUMENTO YA EXISTEN // CAMBIELO//");</script>';
            echo 'script>windows.location="./usuario.php"</script>';
        } else {
            $insertsql = $conex->prepare("INSERT INTO usuario(documento,codigo_barras,nombres,contrasena,id_rol,id_estados,correo,id_tipo_documento,nit_empresa) VALUES (?,?,?,?,?,?,?,?,?)");
            $insertsql->execute([$cedula, $cedula, $nombres, $incri, $rol, 1, $correo, $tipo, $nit_empresa]);
            echo '<script>alert ("Usuario Creado exitosamente, Gracias");</script>';
            echo '<script>window.location="../index.php"</script>';
        }
 


}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../src/img/camara-de-cctv.png" type="image/x-icon">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
    <!-- dirección para que funcione solo número -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body class="bg-gradient-primary">
    <a class="btn btn success" href="../index.php" style="margin-left: 3.6%; margin-top:3%; position:absolute;">
        <i class="bi bi-chevron-left" style="padding:10px 14px 10px 10px; color:#fff; font-size:15px; background-color:#29CA8E; border-radius:10px;">REGRESAR</i>
    </a><br><br><br>
    <form method="post" autocomplete="off" name="cli" enctype="multipart/form-data" onsubmit="return validarContrasena();">
        <div class="container">
            <div class="card o-hidden border-0 shadow-lg my-6">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-10">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 mb-4">Crear Usuario</h1>
                                </div>
                                <form class="user">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-2">
                                            <label>Tipo de Documento</label>
                                            <select name="tipo" class="form-control form-control-user" id="exampleFirstName" required>
                                                <option value="">Elegir</option>
                                                <?php
                                                do {
                                                ?>
                                                <option value="<?php echo ($query3['id_tipo_documento']) ?>">
                                                    <?php echo ($query3['nom_doc']) ?>
                                                </option>
                                                <?php
                                                } while ($query3 = $control3->fetch());
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-6 mb-sm-2">
                                            <label>Documento</label>
                                            <input type="text" style="margin-bottom:5px;" class="form-control form-control-user" id="documento" name="documento" placeholder="Documento" required maxlength="11" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="validarDocumento(event)">
                                        </div>
                                        <div class="col-sm-6 mb-3 mb-sm-2">
                                            <label>Nombres</label>
                                            <input type="text" class="form-control form-control-user" id="nombres" name="nombres" placeholder="Nombres" required maxlength="30" oninput="validarNombre(event)">
                                        </div>
                                        <div class="col-sm-6 mb-3 mb-sm-2">
                                            <label>Correo</label>
                                            <input type="email" class="form-control form-control-user" id="correo" name="correo" placeholder="Correo electrónico" required>
                                        </div>
                                        <div class="col-sm-6 mb-3 mb-sm-2">
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
                                        <div class="col-sm-6 mb-3 mb-sm-2">
                                            <label>Nit Empresa</label>
                                            <select name="nit_empresa" class="form-control form-control-user" id="exampleFirstName" required>
                                                <option value="">Elegir</option>
                                                <?php
                                                if ($query6) { // Verificar si hay resultados en $query6
                                                    do {
                                                ?>
                                                <option value="<?php echo ($query6['nit_empresa']) ?>">
                                                    <?php echo ($query6['nit_empresa']) ?>
                                                </option>
                                                <?php
                                                    } while ($query6 = $control6->fetch());
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-3 mb-sm-2">
                                            <label>Contraseña</label>
                                            <input type="password" class="form-control form-control-user" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                                        </div>
                                    </div>
                                    <input type="submit" style="margin-top:10px;" class="btn btn-primary btn-user btn-block" name="Suscribir">
                                    <input type="hidden" name="validar_V" value="cli">
                                </form>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
    function validarContrasena() {
        var contrasena = document.getElementById("contrasena").value;
        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        if (!regex.test(contrasena)) {
            alert("La contraseña debe contener al menos una mayúscula, un número y tener una longitud mínima de 8 caracteres.");
            return false;
        }
        return true;
    }

    function validarNombre(event) {
        var input = event.target;
        var nombre = input.value;
        // Verificar si el nombre contiene solo letras
        if (!/^[a-zA-Z\s]*$/.test(nombre)) {
            alert("El nombre debe contener solo letras.");
            input.value = ''; // Limpiar el campo
        }
    }

    function validarDocumento(event) {
        var input = event.target;
        var documento = input.value;
        // Verificar si el documento contiene solo números
        if (!/^\d+$/.test(documento)) {
            alert("El documento debe contener solo números.");
            input.value = ''; // Limpiar el campo
        }
    }
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>
</html>