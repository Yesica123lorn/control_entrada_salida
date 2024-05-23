<?php
session_start();
require_once("db/conexion.php");
$db = new DataBase();
$con = $db->conectar();

if (isset($_POST['recuperar'])) {
    $correo = $_POST['email'];

    $digitos = "sakur02ue859y2u389rhdewirh102385y1285013289";
    $longitud = 4;
    $codigo = substr(str_shuffle($digitos), 0, $longitud);

    try {
        $sql = $con->prepare("UPDATE usuario SET codigo=:codigo WHERE correo=:correo");
        $sql->bindParam(':codigo', $codigo);
        $sql->bindParam(':correo', $correo);
        $sql->execute();

        $titulo = "Prueba php";
        $msj = "Su codigo de verificacion es: '$codigo'";
        $tucorreo = "From:yesicagomezrueda42@gmail.com";
        $enviado = mail($correo, $titulo, $msj, $tucorreo);

        if ($enviado) {
            echo '<script>alert("Su código ha sido enviado al correo anteriormente digitado");</script>';
        } else {
            echo '<script>alert("Correo Invalido");</script>';
        }

        echo '<script>window.location="verificar.php";</script>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <title>Restablecer</title>
  <style>
    /* Estilos para el formulario de recuperación */
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

    .image-logo {
      width: 200px;
      height: auto;
      margin-bottom: 20px;
    }

    .card {
      border: none;
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: #f9f9f9;
      border-bottom: none;
      text-align: center;
      padding: 20px;
    }

    .btn-primary {
      width: 100%;
    }
  </style>
</head>
<body>

<div class="container login-container">
  <div class="card">
    <div class="card-header">
      <h4>Digite Su Correo</h4>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="form-group">
          <input class="form-control" name="email" type="text" placeholder="Email">
        </div>
        <button type="submit" name="recuperar" class="btn btn-primary">Restablecer</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
