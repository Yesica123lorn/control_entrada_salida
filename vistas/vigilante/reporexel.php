<?php
session_start();
require_once("../../base_datos/bd.php");
$daba = new Database();
$conex = $daba->conectar();

if (isset($_POST["validar_V"])){

    $fechaini =  $_POST['Fecha_sus'];
    $fechafin =  $_POST['fecha_exp'];
    $horaini =  $_POST['hora_sus'];
    $horafin =  $_POST['hora_exp'];

    $SQL = $conex->prepare ("SELECT*FROM usuarios INNER JOIN tip_user ON usuarios.tipo_usuario = tip_user.id_tip_user INNER JOIN genero ON usuarios.genero=genero.id_genero INNER JOIN estado ON usuarios.estado=estado.id_estado INNER JOIN ejercicio ON usuarios.ejercicio=ejercicio.id_ejercicio ORDER BY usuarios.tipo_usuario = '3' WHERE usuarios.fecha_registro >= 'fechaini' AND usuarios.fecha_registro < 'fechafin' AND usuarios.hora_registro >= 'horaini' AND usuarios.hora_registro < 'horafin'");
    $SQL -> execute();
    $resul=$SQL->fetchAll();

    ?>
    <table class="table table-dark table-sm" style="text-align: center;">
        <tr>
            <th scope="col">DOCUMENTO</th>
            <th>NOMBRE</th>
            <th>USUARIO</th>
            <th>TIPO DE USUARIO</th>
            <th>ESTADO</th>
        </tr>

        <?php

        foreach ($resul as $usu) {

        ?>

            <tr>
                <td><?= $usu['documento'] ?></td>
                <td><?= $usu['nom_completo'] ?></td>
                <td><?= $usu['usuario'] ?></td>
                <td><?= $usu['nom_tip_user'] ?></td>
                <td><?= $usu['nom_genero'] ?></td>
                <td><?= $usu['nom_ejercicio'] ?></td>
                <td><?= $usu['estado'] ?></td>
            </tr>


        <?php
    echo '<script>alert("SE DESCARGO EXITOSAMENTE");</script>';
    echo '<script>window.location="index.php"</script>';
    exit();
        }
    }        
        ?>

</table>
