<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Usar una ruta absoluta para el archivo de log
$logFile = __DIR__ . '/debug.log';

// Registrar el inicio de la ejecución del script
file_put_contents($logFile, "Script iniciado: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

// Registrar todos los datos recibidos
file_put_contents($logFile, "Datos recibidos: " . print_r($_POST, true) . "\n", FILE_APPEND);

require_once 'BD/Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents($logFile, "Método POST detectado\n", FILE_APPEND);
    
    $conexion = new Conexion();
    $conn = $conexion->getcon();

    file_put_contents($logFile, "Conexión establecida\n", FILE_APPEND);

    $idUsuario = $_POST['idUsuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $genero = $_POST['genero'];
    $rol = $_POST['rol'];

    try {
        $stmt = $conn->prepare("CALL SP_ACTUALIZAR_USUARIO(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(3, $apellido, PDO::PARAM_STR);
        $stmt->bindParam(4, $email, PDO::PARAM_STR);
        $stmt->bindParam(5, $fechaNacimiento, PDO::PARAM_STR);
        $stmt->bindParam(6, $genero, PDO::PARAM_STR);
        $stmt->bindParam(7, $rol, PDO::PARAM_STR);

        file_put_contents($logFile, "Intentando ejecutar el procedimiento almacenado\n", FILE_APPEND);

        $stmt->execute();

        file_put_contents($logFile, "Procedimiento almacenado ejecutado con éxito\n", FILE_APPEND);

        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
    } catch (PDOException $e) {
        file_put_contents($logFile, "Error PDO: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    }
} else {
    file_put_contents($logFile, "Método no permitido\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

file_put_contents($logFile, "Script finalizado\n\n", FILE_APPEND);
