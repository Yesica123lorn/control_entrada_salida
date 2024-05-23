<?php
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= usuario.xls");

session_start();
require_once("../../base_datos/bd.php");
$cone=new Database();
$conex=$cone->conectar();
//creamos la consulta
$SQL = $conex->prepare ("SELECT*FROM usuarios INNER JOIN tip_user ON usuarios.tipo_usuario = tip_user.id_tip_user INNER JOIN genero ON usuarios.genero=genero.id_genero INNER JOIN estado ON usuarios.estado=estado.id_estado  ORDER BY usuarios.tipo_usuario ASC");
$SQL -> execute();
$resul=$SQL->fetchAll();
?>

<table>
        <!--El tr nos sirve sirve para crear las filas-->
        <!--El th se crea la cabecera-->
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombre Completo</th>
                <th>usuario</th>
                <th>Tipo Usuario</th>
                <th>Genero</th>
            
                <th>Estado</th>
                
            </tr>
        </thead>

        <?php
        foreach ($resul as $usu) {
            //se abre el ciclo con la llave
        ?>
            <!--El td sirve para sirve para crear las columnas-->
            <!--En cada td se va a mostrar los datos de una tabla usando variables por ejemplo: $variable['nombre del campo de la tabla que queremos que se vea']-->
            <tr>
                <td><?= $usu['documento'] ?></td>
                <td><?= $usu['nom_completo'] ?></td>
                <td><?= $usu['usuario'] ?></td>
                <td><?= $usu['nom_tip_user'] ?></td>
                <td><?= $usu['nom_genero'] ?></td>
                
                <td><?= $usu['estado'] ?></td>
           
                

                <!--con este metodo GET vamos a poder ver la informacion que estamos enviando-->
            </tr>

        <?php
        } //se cierra el recorrido cerrando la llave
        ?>
</table>