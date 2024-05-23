<?php
    session_start();
    require_once("db/conexion.php");
    $db = new Database();
    $con = $db -> conectar();


if (isset($_POST['verificar']))
    { 
        $codigo=$_POST['codigo'];

        $sql= $con -> prepare ("SELECT * FROM usuario WHERE codigo='$codigo'");
        $sql -> execute();
        $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

      if ($fila) {
      echo '<script> alert ("Su codigo ha sido verificado correctamente");</script>';
      echo '<script>window.location="recuperacion1.php"</script>';
      }
      else{
        echo '<script> alert ("El codigo digitado no coincide con el codigo enviado");</script>';
        echo '<script>window.location="verificar.php"</script>';
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

    .input-form {
      margin-bottom: 20px;
    }

    .effect-1 {
      border: none;
      border-bottom: 2px solid #ddd;
      width: 100%;
      padding: 10px 0;
      background-color: transparent;
      transition: border-bottom-color 0.3s;
      font-size: 16px;
    }

    .effect-1:focus {
      outline: none;
      border-bottom-color: #007bff;
    }

    .btn-primary {
      width: 100%;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<div class="container login-container">
  <div class="card">
    <div class="card-header">
      <h4>Digite su código</h4>
    </div>
    <div class="card-body">
      <div class="input-form password-toggle">
        <form method="post">
          <input class="effect-1" name="codigo" id="c" type="text" placeholder="Código">
          <!-- Mover el botón dentro del formulario -->
          <button type="submit" name="verificar" class="btn btn-primary">Verificar</button>
        </form>
      </div>
    </div>
  </div>
</div>


</body>
</html>
