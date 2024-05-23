<?php
class Database
{
    private $hostname = "localhost";
    private $database = "coretick_sis";
    private $username = "root";
    private $password = "123456";
    private $charset = "utf8";
    function conectar()
    {
        try {
            $co = "mysql:host=" . $this->hostname . ";dbname=" . $this->database . ";charset=" . $this->charset;
            $opti = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $base = new PDO($co, $this->username, $this->password, $opti);
            return $base;
        } catch (PDOException $e) {
            echo 'Error de conexion' . $e->GetMessage();
            exit;
        }
    }


}
?>