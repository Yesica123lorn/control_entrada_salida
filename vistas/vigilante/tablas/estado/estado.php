<?php
session_start();
require_once ("../../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();

if (isset($_POST['dispoacti'])) {
    $serial = $_POST['dispoacti'];

    $insertsql = $conex->prepare("UPDATE dispositivos SET estado = 1  WHERE serial ='$serial'");
    $insertsql->execute();
    echo '<script>alert("SE A ACTIVADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../dispositivos.php"</script>';
} elseif (isset($_POST['inadispo'])) {
    $serial = $_POST['inadispo'];

    $insertsql = $conex->prepare("UPDATE dispositivos SET estado = 2  WHERE serial ='$serial'");
    $insertsql->execute();
    echo '<script>alert("SE A INAVILITADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../dispositivos.php"</script>';
}
if (isset($_POST['actiplaca'])) {
    $docu = $_POST['actiplaca'];

    $insertsql = $conex->prepare("UPDATE vehiculos SET estado = 1  WHERE id_placa ='$docu'");
    $insertsql->execute();
    echo '<script>alert("SE A ACTIVADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../vehiculos.php"</script>';
} elseif (isset($_POST['inaplaca'])) {
    $docu = $_POST['inaplaca'];

    $insertsql = $conex->prepare("UPDATE vehiculos SET estado = 2  WHERE id_placa ='$docu'");
    $insertsql->execute();
    echo '<script>alert("SE A INAVILITADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../vehiculos.php"</script>';
}
if (isset($_POST['actiinstu'])) {
    $docu = $_POST['actiinstu'];

    $insertsql = $conex->prepare("UPDATE usuario SET id_estados = 1  WHERE documento ='$docu'");
    $insertsql->execute();
    echo '<script>alert("SE A ACTIVADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../instructores.php"</script>';
} elseif (isset($_POST['inainstu'])) {
    $docu = $_POST['inainstu'];

    $insertsql = $conex->prepare("UPDATE usuario SET id_estados = 2  WHERE documento ='$docu'");
    $insertsql->execute();
    echo '<script>alert("SE A INAVILITADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../instructores.php"</script>';
}
if (isset($_POST['activisa'])) {
    $docu = $_POST['activisa'];

    $insertsql = $conex->prepare("UPDATE usuario SET id_estados = 1  WHERE documento ='$docu'");
    $insertsql->execute();
    echo '<script>alert("SE A ACTIVADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../visitante.php"</script>';
} elseif (isset($_POST['inavisa'])) {
    $docu = $_POST['inavisa'];

    $insertsql = $conex->prepare("UPDATE usuario SET id_estados = 2  WHERE documento ='$docu'");
    $insertsql->execute();
    echo '<script>alert("SE A INAVILITADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../visitante.php"</script>';
}
if (isset($_POST['actiapre'])) {
    $docu = $_POST['actiapre'];

    $insertsql = $conex->prepare("UPDATE usuario SET id_estados = 1  WHERE documento ='$docu'");
    $insertsql->execute();
    echo '<script>alert("SE A ACTIVADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../aprendiz.php"</script>';
} elseif (isset($_POST['inaapre'])) {
    $docu = $_POST['inaapre'];

    $insertsql = $conex->prepare("UPDATE usuario SET id_estados = 2  WHERE documento ='$docu'");
    $insertsql->execute();
    echo '<script>alert("SE A INAVILITADO CORRECTAMENTE");</script>';
    echo '<script>window.location="../aprendiz.php"</script>';
}
if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];
    date_default_timezone_set('America/Bogota');
    $fecha_hora_actual = date("Y-m-d H:i:s");
    $insertsql = $conex->prepare("UPDATE entrada_salidas SET estado = 2, salida_fecha_hora = '$fecha_hora_actual' WHERE id_entrada_salida = ?");
    $insertsql->execute([$estado]);
    echo '<script>alert("Se realizó la salida exitosamente");</script>';
    echo '<script>window.location="../../crear/salida.php"</script>';
}