<?php
require_once("../db/conexion.php");
$db = new Database();
$conexion = $db->conectar();

if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header('Location: ../index.html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["validar_V"]) && $_POST["validar_V"] === "registrar") {
    $nit_empresa = $_POST['nit_empresa'];
    $telefono = $_POST['telefono'];
    $nombre = $_POST['nombre'];
   
    if ($nit_empresa == "" || $telefono == "" || $nombre == "") {
        echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
        echo '<script>window.location="crear.php"</script>';
    } else {
        $consulta_existencia = $conexion->prepare("SELECT * FROM empresas WHERE nit_empresa = ?");
        $consulta_existencia->execute([$nit_empresa]);
        $existe_empresa = $consulta_existencia->fetch();
        

        if ($existe_empresa) {
            echo '<script>alert ("La empresa ya existe. Por favor, cambie los datos.");</script>';
            echo '<script>window.location="crear.php"</script>';
        } else {
            $insertsql = $conexion->prepare("INSERT INTO empresas(nit_empresa,telefono,nombre) VALUES (?,?,?)");
            $insertsql->execute([$nit_empresa, $telefono, $nombre]);
            echo '<script>alert ("Empresa creada exitosamente.");</script>';
            echo '<script>window.location="crearlicencia.php"</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignacion</title>
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
                        <input class="btn btn-outline-danger my-2 my-sm-0" type="submit" value="Cerrar sesion" id="btn_quote" name="cerrar_sesion" />
                    </span>
                </form>
            </div>
        </div>
    </nav>
     <div class="modal" id="modal">
        <div class="modal-content">
            <h2 class="text-center mb-4">Panel Administrador</h2>
            <div class="form-group">
                <input type="password" id="passwordInput" class="form-control" placeholder="Digite codigo">
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
                        <h5 class="mb-0">Registrar Empresa</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" onsubmit="return validateNit()">
                        <div class="form-group">
                                <label for="nit_empresa">Nit Empresa</label>
                                <input type="text" class="form-control" id="nit_empresa" name="nit_empresa" title="Debe ser de 10 d��gitos" required minlength="7" maxlength="12" pattern="[0-9\-]*" onkeyup="limpiarNoPermitidos(this)"required >
                            </div>
                            <div class="form-group">
                            <label for="telefono">Telefono</label>
                            <input type="number" class="form-control" id="telefono" name="telefono" minlength="8" maxlength="10" oninput="validateTelefono(this)" required>
                        </div>

                            <div class="form-group">
                                <label for="nombre">Nombre de la Empresa</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" minlength="4" maxlength="50" required>
                            </div>
                            
                            <input type="submit" style="margin-top:10px;"
                                class="btn btn-primary btn-user btn-block" name="validar_V" value="registrar">
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
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
                alert("Contrase�0�9a incorrecta.");
            }
        }

        window.onload = function() {
            document.getElementById("modal").style.display = "block";
        };
    </script>
    
    
    
    
    
    
    
    <script>
        function validateNit() {
    const nitInput = document.getElementById('nit_empresa');
    const nitValue = nitInput.value;
    const nitPattern = /^[0-9-]+$/;

    if (!nitPattern.test(nitValue)) {
        alert('El NIT solo puede contener n��meros y guiones.');
        nitInput.setCustomValidity('El NIT solo puede contener n��meros y guiones.');
        return false;
    } else if (nitValue.length < 10 || nitValue.length > 13) {
        alert('El NIT debe tener entre 10 y 13 caracteres.');
        nitInput.setCustomValidity('El NIT debe llevar un guion y tener entre 10 a 13 caracteres.');
        return false;
    } else {
        nitInput.setCustomValidity('');
        return true;
    }
}


function espacios(input) {
    // Reemplazar los espacios en blanco
    var texto = input.value;
    texto = texto.replace(/\s/g, '');
    input.value = texto;
}



 function validarLetrasNumerosGuiones(input) {
        var valor = input.value.trim();
        var regex = /^[a-zA-Z0-9\- ]*$/;

        if (!regex.test(valor)) {
            alert('El nombre de empresa solo puede contener letras, n��meros y guiones.');
            input.value = valor.replace(/[^a-zA-Z0-9\- ]/g, ''); // Eliminar caracteres no permitidos
        }
    }
    </script>
    
    <script>
        
function limpiarNoPermitidos(input) {
    // Reemplazar todo lo que no sea n��mero o guion con una cadena vac��a
    input.value = input.value.replace(/[^0-9\-]/g, '');
}
    </script>

<script>
    function validateTelefono(input) {
        const telefonoValue = input.value.trim();
        const telefonoNumeros = telefonoValue.replace(/\s/g, ''); // Eliminar espacios en blanco

        // Verificar si el valor contiene solo números y tiene un máximo de 10 dígitos
        if (!(/^\d{0,10}$/).test(telefonoNumeros)) {
            input.setCustomValidity('El teléfono debe contener solo números y tener máximo 10 dígitos.');
        } else {
            input.setCustomValidity(''); // Restablecer mensaje de error
        }
    }
</script>


</body>

</html>
