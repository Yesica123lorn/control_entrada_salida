<?php
require_once ("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['documento'])) {
   header("Location: ../login.php");
   exit;
}

// Obtener información del usuario
$control = $conex->prepare("SELECT * FROM usuario WHERE documento = ?");
$control->execute([$_SESSION['documento']]);
$query = $control->fetch();

if (isset($_POST["btn-crear"])) {
   $imagen = $_FILES['imagen'];

   $allowed_types = ['image/jpeg', 'image/png'];
   if (!in_array($imagen['type'], $allowed_types)) {
      echo '<script>alert ("Solo se permiten archivos de imagen JPG o PNG.");</script>';
      exit;
   }

   if ($imagen['size'] > 400000) {
      echo '<script>alert ("El tamaño del archivo no puede exceder los 400kb.");</script>';
      exit;
   }

   $target_dir = "../../../images/";
   $target_file = $target_dir . basename($imagen["name"]);
   $foto = basename($imagen["name"]);
   move_uploaded_file($imagen["tmp_name"], $target_file);

   $consulta = $conex->prepare("UPDATE usuario SET foto = ? WHERE documento = ?");
   $consulta->execute([$foto, $_SESSION['documento']]);

   echo '<script>alert ("Imagen subida exitosamente.");</script>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Agregar foto</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
   <script src="dist_files/jquery.imgareaselect.js" type="text/javascript"></script>
   <link href="../../../img/logo_gym.png" rel="icon">
   <script src="dist_files/jquery.form.js"></script>
   <link href="../../../css/foto.css" rel="stylesheet">
   <script src="functions.js"></script>
</head>

<body>
   <a class="btn btn success" href="../index.php" style="margin-left: 3.6%; margin-top:3%; position:absolute;">
      <i class="bi bi-chevron-left"
         style="padding:10px 14px 10px 10px; color:#fff; font-size:15px; background-color:#0d6efd; border-radius:10px;">
         REGRESAR</i>
   </a>
   <form action="" method="POST" enctype="multipart/form-data">
      <div class="container">
         <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
               <div class="row">
                  <div class="col-lg-10">
                     <div class="p-5">
                        <img src="../../../images/<?= $query['foto'] ?>" alt="Imagen del dispositivo"
                           style="width:16%; margin-top:12%; margin-left:56%;">
                        <div style="margin-top:8%; color:#fff; widht:22%; font-size:25px;">Añadir
                           imagen:<br><br>
                           <input name="imagen" class="sele" id="archivo" type="file" /><br>
                           <input type="submit" name="btn-crear" class="subir" value="Subir imagen"
                              style="color:#000; border-radius:10px; background-color:#edb612; border-color:#edb612;" />
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
</body>

</html>