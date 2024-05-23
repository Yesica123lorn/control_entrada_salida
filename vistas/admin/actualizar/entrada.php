<?php
require_once ("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();

$editar = isset($_GET['editar']) ? $_GET['editar'] : "";

$control = $conex->prepare("SELECT * FROM rol");
$control->execute();
$query = $control->fetch();


$control1 = $conex->prepare("SELECT * FROM tipo_entrada");
$control1->execute();
$query1 = $control1->fetch();

date_default_timezone_set('America/Bogota');
$fecha_hora_actual = date("Y-m-d H:i:s");

$validar1 = $conex->prepare("SELECT * FROM entrada_salidas WHERE id_entrada_salida = '$editar'");
$validar1->execute();
$queryi1 = $validar1->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["validar_V"]) && $_POST["validar_V"] === "cli") {
    $cedula = isset($_POST['documento']) ? $_POST['documento'] : "";
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : "";
    $respuesta = isset($_POST['respuesta']) ? $_POST['respuesta'] : "";
    $serial = isset($_POST['serial']) ? $_POST['serial'] : "";
    $placa = isset($_POST['placa']) ? $_POST['placa'] : "";

    if (empty($cedula) || empty($tipo)) {
        echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
        echo '<script>window.location="instructor.php"</script>';
    } else {
        $validar = $conex->prepare("SELECT * FROM usuario LEFT JOIN dispositivos ON usuario.documento=dispositivos.documento LEFT JOIN vehiculos ON usuario.documento=vehiculos.documento WHERE usuario.documento = :cedula");
        $validar->bindParam(':cedula', $cedula);
        $validar->execute();
        $queryi = $validar->fetch(PDO::FETCH_ASSOC);

        if ($queryi) {
            if ($tipo == 1) {
                if (($queryi['id_placa'] === $placa) && ($queryi['serial'] === $serial)) {
                    $updateSql = $conex->prepare("UPDATE entrada_salidas SET entrada_fecha_hora = ?, tipo_entrada = ?, estado = ? WHERE documento = ? AND id_placa = ? AND serial = ?");
                    $updateSql->execute([$fecha_hora_actual, $tipo, 1, $cedula, $placa, $serial]);
                    echo '<script>alert ("Entrada Actualizada");</script>';
                    echo '<script>window.location="./entrada.php"</script>';
                } elseif ($queryi['id_placa'] === $placa) {
                    $updateSql = $conex->prepare("UPDATE entrada_salidas SET entrada_fecha_hora = ?, tipo_entrada = ?, estado = ? WHERE documento = ? AND id_placa = ?");
                    $updateSql->execute([$fecha_hora_actual, $tipo, 1, $cedula, $placa]);
                    echo '<script>alert ("Entrada Actualizada");</script>';
                    echo '<script>window.location="./entrada.php"</script>';
                }
                // Agrega más condiciones de actualización según tu lógica de negocio
            } else {
                echo '<script>alert ("Ocurrio un error con el tipo de entrada");</script>';
                echo '<script>window.location="instructor.php"</script>';
            }
        } else {
            echo '<script>alert ("Usuario no encontrado");</script>';
            echo '<script>window.location="../index.php"</script>';
        }
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

    <title>Entrada</title>

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
    <form method="post" autocomplete="off" name="cli">
        <div class="container">
            <div class="card o-hidden border-0 shadow-lg my-6">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-10">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 mb-4">Entrada</h1>
                                </div>
                                <form class="user">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-6 mb-sm-2">
                                            <label>Documento</label>
                                            <input type="number" style="margin-bottom:5px;"
                                                class="form-control form-control-user" id="exampleFirstName"
                                                pattern="(?=.*\e)[0-9]{6,10}" maxlength="10" name="documento" min="3"
                                                placeholder="Numero de documento" oninput="maxlengthNumber(this);"
                                                value="<?php echo $queryi1['documento'] ?>"
                                                title="Solo se aceptan numeros" required>
                                        </div>
                                        <div class="col-sm-6 mb-3 mb-sm-2">
                                            <label>Tipo de Entrada</label>
                                            <select name="tipo" class="form-control form-control-user" id="tipoEntrada"
                                                required>
                                                <option><?php echo $queryi1['tipo_entrada'] ?></option>
                                                <?php
                                                do {
                                                    ?>
                                                <option value="<?php echo $query1['id_tipo_entrada']; ?>">
                                                    <?php echo $query1['nom_tipo']; ?>
                                                </option>
                                                <?php
                                                } while ($query1 = $control1->fetch());
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group row">
                                            <!-- Resto de los campos de entrada -->
                                            <div class="col-sm-6 mb-3 mb-sm-2" id="placaInput"
                                                style="display: <?php echo isset($queryi1['id_placa']) ? 'block' : 'none'; ?>">
                                                <label>Placa</label>
                                                <input type="text" class="form-control form-control-user" name="placa"
                                                    placeholder="Ingrese la placa"
                                                    value="<?php echo isset($queryi1['id_placa']) ? $queryi1['id_placa'] : ''; ?>">
                                            </div>

                                            <div class="col-sm-6 mb-6 mb-sm-2" style="margin-top: 10px;">
                                                <label>¿Algun Dispositivo?</label>
                                                <label for="si">Sí:</label>
                                                <input type="radio" id="si" name="respuesta" value="si"
                                                    onclick="mostrarSerial()"
                                                    <?php echo isset($queryi1['respuesta']) && $queryi1['respuesta'] === 'si' ? 'checked' : ''; ?>>

                                                <label for="no">No:</label>
                                                <input type="radio" id="no" name="respuesta" value="no"
                                                    onclick="ocultarSerial()"
                                                    <?php echo isset($queryi1['respuesta']) && $queryi1['respuesta'] === 'no' ? 'checked' : ''; ?>>
                                            </div>

                                            <div class="col-sm-6 mb-6 mb-sm-2" id="divSerial"
                                                style="display: <?php echo isset($queryi1['serial']) ? 'block' : 'none'; ?>">
                                                <label>Serial</label>
                                                <input type="text" style="margin-bottom:5px;"
                                                    class="form-control form-control-user" id="serialInput"
                                                    name="serial" placeholder="Digita si vas a entrar con dispositivos"
                                                    value="<?php echo isset($queryi1['serial']) ? $queryi1['serial'] : ''; ?>">
                                            </div>
                                        </div>

                                        <script>
                                        document.getElementById('tipoEntrada').addEventListener('change', function() {
                                            var selectedOption = this.value;
                                            var placaInput = document.getElementById('placaInput');
                                            if (selectedOption === '1') {
                                                placaInput.style.display = 'block';
                                            } else {
                                                placaInput.style.display = 'none';
                                            }
                                        }); <
                                        script >
                                            function mostrarSerial() {
                                                document.getElementById("divSerial").style.display = "block";
                                            }

                                        function ocultarSerial() {
                                            document.getElementById("divSerial").style.display = "none";
                                        }
                                        </script>

                                        <!-- SOLO NUMERO,LONGITUD -->
                                        <script>
                                        o


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
                                                if (key.charCode < 48 || key.charCode > 57)
                                                    return false;
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