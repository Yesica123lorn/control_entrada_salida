<?php
require_once("../db/conexion.php");
$db = new Database();
$conectar = $db->conectar();
session_start();

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Definir las nuevas fechas
    $fecha_inicio = date('Y-m-d H:i:s');
    $fecha_fin = date('Y-m-d H:i:s', strtotime('+2 year'));
    $estado = 1;

    // Actualizar la licencia si existe
    $updateSql = $conectar->prepare("UPDATE licencias SET fecha_fin = ?, id_estado = ? WHERE licencia = ?");
    $updateSql->execute([$fecha_fin, $estado, $id]);

    // Verificar si se actualizó alguna fila
    if ($updateSql->rowCount() > 0) {
        echo '<script>alert("Licencia activa.");</script>';
        echo '<script>window.location="licencia.php"</script>';
    } else {
        echo '<script>alert("No se encontró la licencia.");</script>';
        echo '<script>window.location="licencia.php"</script>';
    }
}
?>
