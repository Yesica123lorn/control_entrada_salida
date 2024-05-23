<?php
require_once("../../../db/conexion.php");
$daba = new Database();
$conex = $daba->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["documento"])) {
        $documento = $_POST["documento"];

        // Verificar si el documento existe en la tabla usuario
        $verificarDocumento = $conex->prepare("SELECT * FROM usuario WHERE documento = :documento");
        $verificarDocumento->bindParam(':documento', $documento);
        $verificarDocumento->execute();
        $usuarioExistente = $verificarDocumento->fetch();

        if (!$usuarioExistente) {
            echo '<script>alert("El documento proporcionado no existe en la base de datos."); window.location.href = "entrada.php";</script>';
            exit; // Detener la ejecución del script para evitar la inserción incorrecta
        }

        // Consultar si la persona tiene una entrada activa sin salida
        $validarEntrada = $conex->prepare("SELECT * FROM entrada_salidas WHERE documento=:documento AND estado = 1");
        $validarEntrada->bindParam(':documento', $documento);
        $validarEntrada->execute();
        $entradaActiva = $validarEntrada->fetch();

        // Si hay una entrada activa, no permitir registrar una nueva entrada
        if ($entradaActiva) {
            echo '<script>alert("Esta persona ya tiene una entrada activa. No puede registrar una nueva entrada hasta que registre una salida."); window.location.href = "entrada.php";</script>';
        } else {
            // Si no hay una entrada activa, permitir registrar una nueva entrada

            // Obtener serial del dispositivo activo asociado al documento seleccionado
            $obtenerSerial = $conex->prepare("SELECT serial FROM dispositivos WHERE documento = :documento AND estado = 1");
            $obtenerSerial->bindParam(':documento', $documento);
            $obtenerSerial->execute();
            $serialRow = $obtenerSerial->fetch();
            $serial = $serialRow ? $serialRow['serial'] : "No aplica";

            // Obtener la placa según el tipo de entrada
            $tipoEntrada = isset($_POST['tipo']) ? $_POST['tipo'] : 0;
            if ($tipoEntrada == 1) { // Si es entrada vehicular
                // Obtener la placa del vehículo asociado al documento
                $obtenerPlaca = $conex->prepare("SELECT id_placa FROM vehiculos WHERE documento = :documento");
                $obtenerPlaca->bindParam(':documento', $documento);
                $obtenerPlaca->execute();
                $placaRow = $obtenerPlaca->fetch();
                $id_Placa = $placaRow ? $placaRow['id_placa'] : null;
                
                // Si no se encontró una placa asociada al documento, evitar el registro de la entrada vehicular
                if (!$id_Placa) {
                    echo '<script>alert("No hay una placa registrada para este documento. No se puede registrar la entrada vehicular."); window.location.href = "entrada.php";</script>';
                    exit;
                }
            } else {
                $id_Placa = "No aplica"; // Si es entrada peatonal, establecer la placa como "No aplica"
            }

            // Insertar nueva entrada en la base de datos
            $insertsql = $conex->prepare("INSERT INTO entrada_salidas (documento, entrada_fecha_hora, salida_fecha_hora, tipo_entrada, id_placa, serial, estado) VALUES (?, NOW(), NOW(), ?, ?, ?, ?)");
            $insertsql->execute([$documento, $tipoEntrada, $id_Placa, $serial, 1]);

            // Redirigir al index después de mostrar el mensaje de entrada registrada
            echo '<script>alert("Entrada registrada exitosamente"); window.location.href = "entrada.php";</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada</title>
    <link rel="stylesheet" href="../../../css/stiledi.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
</head>
<body class="bg-gradient-primary">
    <div class="container mt-4">
        <form method="post" autocomplete="off" name="cli">
            <div class="mb-3">
                <label for="documento" class="form-label">Documento</label>
                <input type="text" class="form-control" id="documento" name="documento" required>
            </div>
            <div class="mb-3">
                 <label for="tipoEntrada" class="form-label">Tipo de Entrada</label>
                    <select class="form-select" id="tipoEntrada" name="tipo" required>
                    <option value="1">Vehicular</option>
                    <option value="2">Peatonal</option>
                </select>
            </div>

            <div class="mb-3 select-placa" style="display: none;">
                <label for="placa" class="form-label">Placa</label>
                <input type="text" class="form-control" id="placa" name="placa" value="<?php echo isset($id_Placa) ? $id_Placa : ''; ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Entrada</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tipoEntrada = document.getElementById('tipoEntrada');
            var selectPlaca = document.querySelector('.select-placa');
            var placaInput = document.getElementById('placa');

            tipoEntrada.addEventListener('change', function () {
                if (tipoEntrada.value === '1') {
                    // Si es entrada vehicular, mostrar el campo de placa
                    selectPlaca.style.display = 'block';
                } else {
                    // Si no es entrada vehicular, ocultar el campo de placa
                    selectPlaca.style.display = 'none';
                    // Establecer el valor de la placa como "No aplica"
                    placaInput.value = 'No aplica';
                }
            });

            // Validar antes de enviar el formulario
            document.querySelector('form').addEventListener('submit', function (event) {
                if (tipoEntrada.value === '1' && placaInput.value === '') {
                    // Si es entrada vehicular y la placa está vacía, evitar enviar el formulario y mostrar un mensaje de alerta
                    alert('Por favor, ingrese una placa para la entrada vehicular.');
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
