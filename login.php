<?php
session_start();

require_once("db/conexion.php");
$db = new Database();
$conectar = $db->conectar();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="shortcut icon" href="./src/img/camara-de-cctv.png" type="image/x-icon">
    <title>SIS - Login</title>
    <style>
        body {
            background-image: url('./src/img/fondo.jpeg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
        }
    </style>
</head>

<body>

    <div class="container login-container">
        <div class="card">
            <div class="card-header text-center">
                <h4>Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="./controller/iniciose.php" >
                    <div class="form-group">
                        <label for="documento">Documento:</label>
                        <input type="text" style="margin-bottom:5px;" class="form-control form-control-user" id="documento" name="documento" placeholder="Documento" required maxlength="11" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="validarDocumento(event)">
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingresa tu contraseña" required>
                    </div>
                    <div class="form-group text-center">
                        <a href="registro.php" class="text-secondary">REGISTRO</a>
                    </div>
                    <div class="form-group text-center">
                        <a href="recuperacion.php" class="text-secondary">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="login_button">Iniciar Sesión</button>
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
                </script>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>