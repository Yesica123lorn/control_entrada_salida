<?php
    session_start();
    require_once ("db/conexion.php");
    $db = new DataBase();
    $con = $db -> conectar();


if (isset($_POST['actualizar'])){ 
  
  $documento=$_POST['documento']; 
  $contrasena= $_POST['contrasena']; 
  $confirmar_contrasena= $_POST['confirmar_contrasena']; 
  
    if($contrasena == $confirmar_contrasena) {
     


     $sql= $con -> prepare ("SELECT * FROM usuario");
     $sql -> execute();
     $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);
   
     if ($documento=="" || $contrasena=="" || $confirmar_contrasena=="")
      {
         echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
         echo '<script>window.location="registro_empleados.php"</script>';
      }

      else{
        $consulta = $con->prepare("SELECT * FROM usuario WHERE documento = 'documento'");
        $consulta -> execute();
        $consul = $consulta -> fetchAll(PDO::FETCH_ASSOC);

      


        $pass_cifrado = password_hash($contrasena,PASSWORD_DEFAULT, array("pass"=>12));

        $insertSQL = $con->prepare("UPDATE usuario SET contrasena='$pass_cifrado' WHERE documento = '$documento' ");
        $insertSQL -> execute();
        echo '<script> alert("REGISTRO EXITOSO");</script>';
        echo '<script>window.location="login.php"</script>';
     }  
    }
    else {
      echo '<script>alert ("LAS CONTRASEÑAS NO COINCIDEN");</script>';
    }
     
  }

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <title>Recuperar Contraseña</title>
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

    .registration {
      padding: 20px;
    }

    .effect-1 {
      border: none;
      border-bottom: 2px solid #ddd;
      width: 100%;
      padding: 10px 0;
      background-color: transparent;
      transition: border-bottom-color 0.3s;
      font-size: 16px;
      margin-bottom: 20px;
    }

    .effect-1:focus {
      outline: none;
      border-bottom-color: #007bff;
    }

    .password-container {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
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
      <h4>Recuperar Contraseña</h4>
    </div>
    <div class="card-body">
      <form method="POST" name="form1" id="form1" autocomplete="off" class="registration"> 
        <div class="password-container">
          <input class="effect-1" name= "documento" type="number" placeholder="Documento">
          <input class="effect-1" name= "contrasena" type="password" placeholder="Nueva Contraseña">
          <span class="toggle-password" onclick="togglePasswordVisibility(this)"></span>
          <input class="effect-1" name= "confirmar_contrasena" type="password" placeholder="Confirmar Contraseña">
          <span class="focus-border"></span>
        </div>
        <br>
        <input type="submit" name="actualizar" value="Actualizar" class="btn btn-primary">
        <input type="hidden" name="MM_insert" value="formreg">
      </form>
    </div>
  </div>
</div>

<script>
  function togglePasswordVisibility(element) {
    var passwordInput = element.parentElement.querySelector('input[type="password"]');
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      element.classList.add('active');
    } else {
      passwordInput.type = "password";
      element.classList.remove('active');
    }
  }
</script>

</body>
</html>
