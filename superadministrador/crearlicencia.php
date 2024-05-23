<?php
require_once("../db/conexion.php");
$db = new Database();
$conexion = $db->conectar();
date_default_timezone_set('America/Bogota');

if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header('Location: ../index.html');
    exit();
}

try {
    $empresa = $conexion->prepare("SELECT nit_empresa, nombre FROM empresas WHERE nit_empresa >= 1");
    $empresa->execute();
    $empresas = $empresa->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar las empresas: " . $e->getMessage());
}

if ((isset($_POST["valida_v"])) && ($_POST["valida_v"] == "cli")) {
    $licencia = uniqid();
    $nit_empresa = $_POST['nit_empresa'];
    $fecha = date('Y-m-d H:i:s');
    $fecha_fin = date('Y-m-d H:i:s', strtotime('+2 years'));
    $id_estados = 1; // Asegúrate de asignar el valor correcto

    try {
        $validar_nit = $conexion->prepare("SELECT * FROM licencias WHERE nit_empresa = ?");
        $validar_nit->execute([$nit_empresa]);

        if ($nit_empresa == "") {
            echo '<script>alert("EXISTEN CAMPOS VACÍOS");</script>';
            echo '<script>window.location="crearlicencia.php"</script>';
        } else {
            $insertsql = $conexion->prepare("INSERT INTO licencias (licencia, fecha, fecha_fin, id_estado, nit_empresa) VALUES (?, ?, ?, ?, ?)");
            $insertsql->execute([$licencia, $fecha, $fecha_fin, $id_estados, $nit_empresa]);
            echo '<script>alert("Empresa creada exitosamente.");</script>';
            echo '<script>window.location="registro_adm.php"</script>';
        }
    } catch (PDOException $e) {
        die("Error al insertar la licencia: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación</title>
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Asignación Licencia</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" class="formulario-pequeño registro_form">
                            <div class="form-group">
                                <label for="empresa">Empresa:</label>
                                <select class="form-control" id="nit" name="nit_empresa" required>
                                    <option value="" disabled selected>Selecciona la empresa</option>
                                    <?php foreach ($empresas as $empresa) : ?>
                                        <option value="<?php echo $empresa['nit_empresa']; ?>"><?php echo $empresa['nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <br>
                                <input type="hidden" name="valida_v" value="cli">
                                <button type="submit" class="btn btn-primary">Asignar</button>
                            </div>
                        </form>
                    </div>
                </div>
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
