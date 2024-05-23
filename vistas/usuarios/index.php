<?php
// Incluir el archivo de conexión a la base de datos
require_once("../../db/conexion.php");
session_start();

// Inicializar $userData como un array vacío para evitar el error de "Undefined variable"
$userData = [];

// Obtener datos del usuario si el documento está en la sesión
if (isset($_SESSION['documento'])) {
    try {
        // Instanciar la clase Database
        $db = new Database();
        // Conectar a la base de datos
        $conn = $db->conectar();

        $documento = $_SESSION['documento'];

        $sql = "SELECT * FROM usuario WHERE documento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$documento]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener los datos del usuario: ' . $e->getMessage();
    } finally {
        // Cerrar la conexión
        $conn = null;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        // Verificar si se ha iniciado sesión y el usuario tiene nombre
        if (!empty($userData['nombres'])) {
            echo htmlspecialchars($userData['nombres']) . " - Datos del Usuario";
        } else {
            echo "Datos del Usuario";
        }
        ?>
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-container {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <?php
            // Verificar si se ha iniciado sesión y el usuario tiene nombre
            if (!empty($userData['nombres'])) {
                echo "Datos de " . htmlspecialchars($userData['nombres']);
            } else {
                echo "Datos del Usuario";
            }
            ?>
        </h1>
        <?php if (!empty($userData)): ?>
            <table>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
                <?php foreach ($userData as $key => $value): ?>
                    <tr>
                        <td><?php echo ucwords(str_replace('_', ' ', $key)); ?>:</td>
                        <td><?php echo htmlspecialchars($value); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="btn-container">
                <a class="btn btn-success" href="excelusuario.php">Exportar a Excel</a>
                <a class="btn btn-danger" href="pdfusuario.php">Descargar PDF</a>
                <button type="button" onclick="location.href='../../index.html'" class="btn btn-secondary">Cerrar Sesión</button>
            </div>
        <?php else: ?>
            <p>No se encontraron datos para el documento proporcionado.</p>
        <?php endif; ?>
    </div>
</body>
</html>

