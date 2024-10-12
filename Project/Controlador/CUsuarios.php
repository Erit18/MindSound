<?php
require_once 'BD/Conexion.php';

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'eliminar':
            eliminarUsuario();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
            break;
    }
}

function eliminarUsuario() {
    $idUsuario = $_POST['idUsuario'] ?? '';
    
    if (!$idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ELIMINAR_USUARIO(?)");
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el usuario']);
        }
    } catch (PDOException $e) {
        error_log("Error al eliminar usuario: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
