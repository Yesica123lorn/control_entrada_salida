<?php
require_once ("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();
session_start();

$editar = $_GET['editar'];

$control = $conex->prepare("SELECT * From tipo_vehiculo ");
$control->execute();
$query = $control->fetch();

$control1 = $conex->prepare("SELECT * FROM marcas LIMIT 1, 9");
$control1->execute();
$query1 = $control1->fetch();

$control2 = $conex->prepare("SELECT * From color LIMIT 0, 7");
$control2->execute();
$query2 = $control2->fetch();


$con = $conex->prepare("SELECT * FROM vehiculos 
LEFT JOIN marcas ON vehiculos.id_marca = marcas.id_marca
LEFT JOIN color ON vehiculos.id_color = color.id_color 
LEFT JOIN tipo_vehiculo ON vehiculos.id_tipo_vehiculo = tipo_vehiculo.id_tipo_vehiculo 
LEFT JOIN estados ON vehiculos.estado = estados.id_estados WHERE vehiculos.id_placa='$editar'");
$con->execute();
$fila = $con->fetch();
?>

<?php
if ((isset($_POST["validar_V"])) && ($_POST["validar_V"] == "user")) {

    $cedula = $_POST['documento'];
    $marca = $_POST['marca'];
    $color = $_POST['color'];
    $tipovehiculo = $_POST['tipovehiculo'];

    $validar1 = $conex->prepare("SELECT * FROM vehiculos WHERE id_placa='$editar'");
    $validar1->execute();
    $queryi1 = $validar1->fetch();

    if ($cedula == "") {

        echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
        echo '<script>window.location="./actu_vehiculo.php"</script>';

    } else if (!$queryi1) {

        echo '<script>alert ("LOS DATOS INGRESADOS SON INCORRECTOS");</script>';
        echo '<script>windows.location="actu_vehiculo.php"</script>';
    } else {
        $insertsql3 = $conex->prepare("UPDATE vehiculos SET documento='$cedula', id_placa='$editar',id_marca='$marca', id_color='$color', id_tipo_vehiculo='$tipovehiculo' WHERE id_placa='$editar'");
        $insertsql3->execute();
        echo '<script>alert ("ACTUALIZACION EXITOSA");</script>';
        echo '<script>window.location="../tablas/vehiculos.php"</script>';
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

    <title>Actualizacion datos</title>

    <link href="../../../img1/logo9.png" rel="icon">
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
    <a class="btn btn success" href="../tablas/vehiculos.php"
        style="margin-left: 3.6%; margin-top:0%; position:absolute;">
        <i class="bi bi-chevron-left"
            style="padding:10px 14px 10px 10px; color:#fff; font-size:15px; background-color:#0d6efd; border-radius:10px;">
            REGRESAR</i>
    </a>

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Actualizacion de Usuario</h1>
                            </div>
                            <form class="user" name="user" method="post">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label>Placa</label>
                                        <input type="text" style="margin-bottom:10px;" class="form-control"
                                            id="exampleFirstName" name="nombre" value="<?php echo $fila['id_placa'] ?>"
                                            readonly>
                                    </div>
                                    <div class="col-sm-6  mb-3 mb-sm-2">
                                        <label>Marca</label>
                                        <select name="marca" class="form-control" id="exampleFirstName" required>
                                            <option value="">
                                                <?php echo ($fila['nom_marca']) ?>
                                            </option>
                                            <?php
                                            do {
                                                ?>
                                            <option value="<?php echo ($query1['id_marca']) ?>">
                                                <?php echo ($query1['nom_marca']) ?>
                                            </option>
                                            <?php
                                            } while ($query1 = $control1->fetch());
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6  mb-3 mb-sm-2">
                                        <label>Color del Vehiculo</label>
                                        <select name="color" class="form-control" id="exampleFirstName" required>
                                            <option value="">
                                                <?php echo ($fila['nom_color']) ?>
                                            </option>
                                            <?php
                                            do {
                                                ?>
                                            <option value="<?php echo ($query2['id_color']) ?>">
                                                <?php echo ($query2['nom_color']) ?>
                                            </option>
                                            <?php
                                            } while ($query2 = $control2->fetch());
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6  mb-3 mb-sm-2">
                                        <label>Tipo del Vehiculo</label>
                                        <select name="tipovehiculo" class="form-control " id="exampleFirstName"
                                            required>
                                            <option value="">E
                                                <?php echo ($fila['nom_vehiculo']) ?>
                                            </option>
                                            <?php
                                            do {
                                                ?>
                                            <option value="<?php echo ($query['id_tipo_vehiculo']) ?>">
                                                <?php echo ($query['nom_vehiculo']) ?>
                                            </option>
                                            <?php
                                            } while ($query = $control->fetch());
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                        <label>Documento Asignado </label>
                                        <input type="number" min="1" style="margin-bottom:10px;" class="form-control"
                                            maxlength="4" oninput="maxlengthNumber(this);" id="exampleFirstName"
                                            name="documento" value="<?php echo $fila['documento'] ?>"
                                            title="Solo se aceptan numeros" readonly>
                                    </div>

                                </div>

                                <!-- SOLO NUMERO,LONGITUD -->
                                <script>
                                function maxlengthNumber(obj) {
                                    console.log(obj.value);
                                    if (obj.value.length > obj.maxLength) {
                                        obj.value = obj.value.slice(0, obj.maxLength);
                                    }
                                }
                                </script>

                                <input type="submit" class="btn btn-primary btn-block" name="enviar">
                                <input type="hidden" name="validar_V" value="user">
                            </form>
                            <hr>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>