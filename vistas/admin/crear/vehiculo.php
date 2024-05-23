<?php
require_once("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();

$control_documentos = $conex->prepare("SELECT documento FROM usuario WHERE id_rol IN (3, 4, 5)");
$control_documentos->execute();
$que = $control_documentos->fetch(PDO::FETCH_ASSOC);

$control = $conex->prepare("SELECT * From tipo_vehiculo ");
$control->execute();
$query = $control->fetch();

$control1 = $conex->prepare("SELECT * FROM marcas LIMIT 1, 9"); 
$control1->execute();
$query1 = $control1->fetch();

$control2 = $conex->prepare("SELECT * From color LIMIT 0, 7");
$control2->execute();
$query2 = $control2->fetch();


?>

<?php
if (isset($_POST["validar_V"]) == "cli") {
    $cedula = $_POST['documento'];
    $placa = $_POST['placa'];
    $marca = $_POST['marca'];
    $color = $_POST['color'];
    $tipovehiculo = $_POST['tipovehiculo'];

    $validar = $conex->prepare("SELECT * FROM vehiculos where id_placa ='$placa' ");
    $validar->execute();
    $queryi = $validar->fetch();

    $validar4 = $conex->prepare("SELECT * FROM usuario WHERE documento='$cedula' ");
    $validar4->execute();
    $queryi4 = $validar4->fetch();

    if ($cedula == "" || $placa == "" || $marca == "" || $color == ""  || $tipovehiculo == "" ) {

        echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
        echo '<script>window.location="vehiculo.php"</script>';
    } else if ($queryi) {
        echo '<script>alert ("El Vehiculo YA EXISTEN // CAMBIELO//");</script>';
        echo 'script>windows.location="vehiculo.php"</script>';
    } elseif ($queryi4) {
        $insertsql = $conex->prepare("INSERT INTO vehiculos(documento,id_placa,id_marca,id_color,id_tipo_vehiculo) VALUES (?,?,?,?,?)");
        $insertsql->execute([$cedula, $placa, $marca,$color, $tipovehiculo]);
        echo '<script>alert ("Vehiculo Creado exitosamente, Gracias");</script>';
        echo '<script>window.location="./vehiculo.php"</script>';
    }else{
        echo '<script>alert ("El usuario no esta registrado // asi que no puede asignar este vehiculo ");</script>';
        echo '<script>window.location="./vehiculo.php"</script>';

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

    <title>Crear Vehiculo</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- direccion para que funcione solo numero -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body class="bg-gradient-primary">
    <a class="btn btn success" href="../index.php" style="margin-left: 3.6%; margin-top:3%; position:absolute;">
        <i class="bi bi-chevron-left"
            style="padding:10px 14px 10px 10px; color:#fff; font-size:15px; background-color:#29CA8E; border-radius:10px;">
            REGRESAR</i>
    </a><br><br><br>
    <form method="post" autocomplete="off" name="cli"  id="formVehiculo">
        <div class="container">
            <div class="card o-hidden border-0 shadow-lg my-6">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-10">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 mb-4" >Crear Vehiculo</h1>
                                </div>
                                <form class="user">
                                    <div class="form-group row" >
                                        <div class="col-sm-6 mb-6 mb-sm-2">
                                            <label>Documento</label>
                                                <select name="documento" class="form-control form-control-user"
                                                id="exampleFirstName" required>
                                                <option value="">Elegir</option>
                                                <?php
                
                                                do {
                
                                                ?>
                                                    <option value=<?php echo ($que['documento']) ?>><?php echo ($que['documento']) ?>  </option>
                                                <?php
                                                } while ($que = $control_documentos->fetch());
                
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-3 mb-sm-2">
                                            <label>Placa</label>
                                            <input type="text" class="form-control form-control-user" id="placa" name="placa" placeholder="Ingresa la placa del vehiculo" required>
                                            <small id="placaHelp" class="form-text text-danger"></small>
                                        </div>

                                        <div class="col-sm-6  mb-3 mb-sm-2">
                                            <label>Marca</label>
                                            <select name="marca" class="form-control form-control-user"
                                                id="exampleFirstName" required>
                                                <option value="">Elegir</option>
                                                <?php
                                                do {
                                                    ?>
                                                    <option value="<?php echo ($query1['id_marca']) ?>"> <?php echo ($query1['nom_marca']) ?> </option>
                                                    <?php
                                                } while ($query1 = $control1->fetch());
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6  mb-3 mb-sm-2">
                                            <label>Color del Vehiculo</label>
                                            <select name="color" class="form-control form-control-user"
                                                id="exampleFirstName" required>
                                                <option value="">Elegir</option>
                                                <?php
                                                do {
                                                    ?>
                                                    <option value="<?php echo ($query2['id_color']) ?>"> <?php echo ($query2['nom_color']) ?> </option>
                                                    <?php
                                                } while ($query2 = $control2->fetch());
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6  mb-3 mb-sm-2">
                                            <label>Tipo del Vehiculo</label>
                                            <select name="tipovehiculo" class="form-control form-control-user"
                                                id="exampleFirstName" required>
                                                <option value="">Elegir</option>
                                                <?php
                                                do {
                                                    ?>
                                                    <option value="<?php echo ($query['id_tipo_vehiculo']) ?>"> <?php echo ($query['nom_vehiculo']) ?> </option>
                                                    <?php
                                                } while ($query = $control->fetch());
                                                ?>
                                            </select>
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

                                        <!-- LONGITUD DE LETRA -->
                                        <script>
                                            function validaletras(obj) {
                                                console.log(obj.value);
                                                if (obj.value.length > obj.maxLength) {
                                                    obj.value = obj.value.slice(0, obj.maxLength);
                                                }
                                            }
                                        </script>

                                        <!-- SOLO LETRA (ESPACIO DE LETRAS SE HACE EN LETRAS) -->
                                        <script>
                                            function sololetras(e) {
                                                key = e.keyCode || e.which;

                                                teclado = String.fromCharCode(key).toLowerCase();

                                                letras = "qwertyuiopasdfghjklñzxcvbnm ";

                                                especiales = "8-37-38-46-164-46";

                                                teclado_especial = false;

                                                for (var i in especiales) {
                                                    if (key == especiales[i]) {
                                                        teclado_especial = true;
                                                        break;
                                                    }
                                                }

                                                if (letras.indexOf(teclado) == -1 && !teclado_especial) {
                                                    return false;
                                                    a
                                                }
                                            }
                                        </script>

                                        <!-- SOLO NUMEROS () -->
                                        <script>
                                            function solonumeros(e) {
                                                key = e.keyCode || e.which;

                                                teclado = String.fromCharCode(key).toLowerCase();

                                                letras = "0123456789.";

                                                especiales = "8-37-38-46-164-46";

                                                teclado_especial = false;

                                                for (var i in especiales) {
                                                    if (key == especiales[i]) {
                                                        teclado_especial = true;
                                                        break;
                                                    }
                                                }

                                                if (letras.indexOf(teclado) == -1 && !teclado_especial) {
                                                    return false;
                                                    a
                                                }
                                            }
                                        </script>

                                        <!-- SOLO NUMERO -->
                                        <script>
                                            $(function() {
                                            $('input[type=number]').keypress(function(key) {
                                                if(key.charCode < 48 || key.charCode > 57) return false;
                                            });
                                        });

                                        </script>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                // Obtenemos el formulario por su ID
                                                var form = document.getElementById("formVehiculo");

                                                // Agregamos un event listener para el evento submit del formulario
                                                form.addEventListener("submit", function(event) {
                                                    var placaInput = document.getElementById("placa");
                                                    var placaValue = placaInput.value;

                                                    // Expresión regular para validar la placa
                                                    var regex = /^[A-Za-z0-9]{0,6}$/;

                                                    // Si la placa no cumple con la expresión regular
                                                    if (!regex.test(placaValue)) {
                                                        // Mostramos un mensaje de error
                                                        document.getElementById("placaHelp").innerText = "La placa debe contener letras y números, con un máximo de 6 caracteres.";
                                                        // Evitamos que el formulario se envíe
                                                        event.preventDefault();
                                                    } else {
                                                        // Si la placa es válida, limpiamos el mensaje de error
                                                        document.getElementById("placaHelp").innerText = "";
                                                    }
                                                });
                                            });
                                        </script>





                                        <input type="submit" style="margin-top:10px;"
                                            class="btn btn-primary btn-user btn-block" name="Suscribir">
                                        <input type="hidden" name="validar_V" value="user">
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
        // FUNCION DE JAVASCRIPT PARA VALIDAR LOS AÑOS DE RANGO PARA LA FECHA DE NACIMIENTO
        var fechaInput = document.getElementById('fecha');
        // Calcular las fechas mínima y máxima permitidas
        var fechaMaxima = new Date();
        fechaMaxima.setFullYear(fechaMaxima.getFullYear() - 14); // Restar 18 años para que la persona se registre 
        var fechaMinima = new Date();
        fechaMinima.setFullYear(fechaMinima.getFullYear() - 60); // Restar 80 años
        // Formatear las fechas mínima y máxima en formato de fecha adecuado (YYYY-MM-DD)
        var fechaMaximaFormateada = fechaMaxima.toISOString().split('T')[0];
        var fechaMinimaFormateada = fechaMinima.toISOString().split('T')[0];

        // Establecer los atributos min y max del campo de entrada de fecha
        fechaInput.setAttribute('min', fechaMinimaFormateada);
        fechaInput.setAttribute('max', fechaMaximaFormateada);
    </script>
    
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>