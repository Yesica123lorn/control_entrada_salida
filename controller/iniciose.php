<?php
require_once ("../db/conexion.php");
$daba = new database();
$conectar = $daba->conectar();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login_button"])) {
    $documento = $_POST['documento'];
    $password = $_POST['contrasena'];

    try {
        $usuario = $conectar->prepare("SELECT * FROM usuario, licencias,estados WHERE licencias.nit_empresa = usuario.nit_empresa AND id_estado= 1 AND documento = ?");
        $usuario->execute([$documento]);
        $usuarios = $usuario->fetch();

        if ($usuarios && password_verify($password, $usuarios['contrasena'])) {
            $_SESSION['documento'] = $documento;
            $_SESSION['rol'] = $usuarios['id_rol'];

            switch ($_SESSION['rol']) {
                case 1:
                    header("Location: ../vistas/admin/index.php");
                    exit();
                case 2:
                    header("Location: ../vistas/vigilante/index.php");
                    exit();
                case 3:
                    // Redirigir a la carpeta 'usuario' para los roles de aprendiz, instructor y visitante
                    header("Location: ../vistas/usuarios/index.php");
                    exit();
                case 4:
                    header("Location: ../vistas/usuarios/index.php");
                    exit();
                case 5:
                    header("Location: ../vistas/usuarios/index.php");
                    exit();
                default:
                    echo "<script>alert('La contraseña no es correcta o expiró su licencia');</script>";
                    echo '<script>window.location="index.html"</script>';
                    exit();
            }
        } else {
            echo "<script>alert('La contraseña no es correcta o expiró su licencia');</script>";
            echo '<script>window.location="../login.php"</script>';
            exit();
        }
    } catch (PDOException $e) {
        // Manejar el error aquí (por ejemplo, loggearlo)
        echo "Error en la consulta: " . $e->getMessage();
        exit();
    }
}
?>
