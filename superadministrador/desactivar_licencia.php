<?php
require_once("../db/conexion.php");
$db = new Database();
$conectar = $db->conectar();
session_start();


if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $nitQuery = $conectar->prepare("SELECT licencia FROM licencias WHERE licencia=?");
    $nitQuery->execute([$id]);
    $nit = $nitQuery->fetch(PDO::FETCH_ASSOC); // Cambiado a fetch


    if ($nit) {

        // Se actualiza el estado de la licencia
        $updateSql = $conectar->prepare("UPDATE licencias SET id_estado = 2 WHERE licencia = ?");
        $updateSql->execute([$id]);
        echo '<script>alert ("licencia desactivada con exito");</script>';
        echo '<script> window.location= "licencia.php"</script>';
    }
}
?>
