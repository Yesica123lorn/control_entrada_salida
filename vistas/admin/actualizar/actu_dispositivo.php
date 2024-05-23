<?php
require_once ("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();
session_start();

$editar = $_GET['editar'];

$control = $conex->prepare("SELECT * FROM tipo_dispositivo");
$control->execute();
$query = $control->fetch();

$control1 = $conex->prepare("SELECT * FROM marcas LIMIT 10, 7");
$control1->execute();
$query1 = $control1->fetch();

$control2 = $conex->prepare("SELECT * FROM color LIMIT 8, 12");
$control2->execute();
$query2 = $control2->fetch();

$con = $conex->prepare("SELECT * FROM dispositivos LEFT JOIN marcas ON dispositivos.id_marca = marcas.id_marca 
LEFT JOIN color ON dispositivos.id_color = color.id_color 
LEFT JOIN tipo_dispositivo ON dispositivos.id_tipo_dispositivo = tipo_dispositivo.id_tipo_dispositivo 
LEFT JOIN estados ON dispositivos.estado = estados.id_estados WHERE dispositivos.serial='$editar'");
$con->execute();
$fila = $con->fetch();
if (isset($_POST["validar_V"]) && $_POST["validar_V"] == "user") {
    // Recuperar datos del formulario
    $serial = $_POST['nombre'];
    $marca = $_POST['marca'];
    $imagen = $_FILES['imagen'];
    $color = $_POST['color'];
    $tipo_dispositivo = $_POST['tipo_dispositivo'];
    $documento_asignado = $_POST['documento_asignado'];

    // Verificar campos vacíos
    if (empty($serial) || empty($marca) || empty($imagen['name']) || empty($color) || empty($tipo_dispositivo) || empty($documento_asignado)) {
        echo '<script>alert("Por favor, complete todos los campos.");</script>';
        echo '<script>window.location="./dispositivo.php"</script>';
        exit;
    }

    // Verificar tipo y tamaño de la imagen
    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($imagen['type'], $allowed_types) || $imagen['size'] > 400000) {
        echo '<script>alert("Solo se permiten archivos de imagen JPG o PNG con un tamaño máximo de 400kb.");</script>';
        echo '<script>window.location="./dispositivo.php"</script>';
        exit;
    }

    // Mover la imagen a la carpeta "images/dispo"
    $target_dir = "../../../images/dispo/";
    $imagen_nombre = basename($imagen["name"]);
    $target_file = $target_dir . $imagen_nombre;

    if (!move_uploaded_file($imagen["tmp_name"], $target_file)) {
        echo '<script>alert ("Hubo un error al cargar la imagen.");</script>';
        echo '<script>window.location="./dispositivo.php"</script>';
        exit;
    }

    // Realizar la actualización en la base de datos
    $update_sql = $conex->prepare("UPDATE dispositivos SET serial=?, id_marca=?, imagen=?, id_color=?, id_tipo_dispositivo=?, documento=? WHERE serial=?");
    $update_sql->execute([$serial, $marca, $imagen_nombre, $color, $tipo_dispositivo, $documento_asignado, $editar]);

    echo '<script>alert ("Actualización exitosa.");</script>';
    echo '<script>window.location="../tablas/tabla_datos.php"</script>';
    exit;
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
    <a class="btn btn success" href="../tablas/dispositivos.php"
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
                                <h1 class="h4 text-gray-900 mb-4">Actualizacion de Dispositivo</h1>
                            </div>
                            <form class="user" name="user" method="post">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label>Serial</label>
                                        <input type="text" style="margin-bottom:10px;" class="form-control"
                                            id="exampleFirstName" name="nombre" value="<?php echo $fila['serial'] ?>"
                                            required>
                                    </div>
                                    <div class="col-sm-6  mb-3 mb-sm-2">
                                        <label>Marca</label>
                                        <select name="marca" class="form-control " id="exampleFirstName" required>
                                            <option value="" style="text-color:black;">
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
                                        <label>Imagen</label>
                                        <input type="file" class="form-control-file" name="imagen" accept="image/*"
                                            required>
                                    </div>
                                    <div class="col-sm-6  mb-3 mb-sm-2">
                                        <label>Color</label>
                                        <select name="marca" class="form-control " id="exampleFirstName" required>
                                            <option value="" style="text-color:black;">
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
                                        <label>Tipo de Dispositivo</label>
                                        <select name="marca" class="form-control " id="exampleFirstName" required>
                                            <option value="" style="text-color:black;">
                                                <?php echo ($fila['nom_dispositivo']) ?>
                                            </option>
                                            <?php
                                            do {
                                                ?>
                                                <option value="<?php echo ($query['id_tipo_dispositivo']) ?>">
                                                    <?php echo ($query['nom_dispositivo']) ?>
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
                                            name="<?php echo $fila['documento'] ?>" name="obesidad"
                                            placeholder="<?php echo $fila['documento'] ?>"
                                            title="Solo se aceptan numeros" required>
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