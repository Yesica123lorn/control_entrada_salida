<?php
require_once("../db/conexion.php");
$db = new Database();
$conexion = $db->conectar();
session_start();

if (isset($_GET["nit_empresa"])) {
    $nit_empresa = $_GET["nit_empresa"];

    // Consulta para verificar si el NIT ya tiene una licencia activa
    $validar_nit = $conexion->prepare("SELECT * FROM licencias WHERE nit_empresa = ? AND licencia = 'activo'");
    $validar_nit->execute([$nit_empresa]);

    // Verifica si ya hay una licencia activa para este NIT
    if ($validar_nit->rowCount() > 0) {
        echo '<script>alert("Este NIT ya tiene una licencia activa");</script>';
        echo '<script>window.location="index.php"</script>';
        exit(); // Termina el script después de redireccionar
    }

    // Si no hay una licencia activa para este NIT, inserta una nueva
    $licencia = uniqid();
    $fecha = date('Y-m-d H:i:s');
    $fecha_fin = date('Y-m-d H:i:s', strtotime('+1 year'));

    try {
        // Asumiendo que $id_estado es el id correspondiente al estado 'activo' en la tabla de estados
        $id_estado = 1; // Este valor debe ser el id correcto según tu tabla de estados

        $insertsql = $conexion->prepare("INSERT INTO licencias (licencia, id_estado, fecha, fecha_fin, nit_empresa) VALUES (?, ?, ?, ?, ?)");
        $insertsql->execute([$licencia, $id_estado, $fecha, $fecha_fin, $nit_empresa]);

        // Si la inserción fue exitosa, muestra un mensaje y redirige
        echo '<script>alert("Registro exitoso");</script>';
        echo '<script>window.location = "licencia.php";</script>';
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
