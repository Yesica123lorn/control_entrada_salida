<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../index.php"); // Cambia "index.php" por la página de inicio de sesión
exit;
?>
