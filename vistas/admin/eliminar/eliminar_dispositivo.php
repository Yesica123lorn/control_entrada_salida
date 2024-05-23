<?php

session_start();
require_once ("../../../db/conexion.php");
$cone = new Database();
$conex = $cone->conectar();
$elimi = $_GET['elimin'];

$delete = $conex->prepare("DELETE FROM dispositivos WHERE serial= '$elimi ' ");
$delete->execute();
$eliminar = $delete->fetch();

if (!$eliminar) {
    echo '<script> alert ("//SE ELIMINARON CORRECTAMENTE LOS DATOS//");</script>';
    echo '<script> window.location="../tablas/dispositivos.php"</script>';

} else {
    echo '<script> alert ("//NO SE ELIMINARON CORRECTAMENTE LOS DATOS//");</script>';
    echo '<script> window.location="../tablas/dispositivos"</script>';
}

?>