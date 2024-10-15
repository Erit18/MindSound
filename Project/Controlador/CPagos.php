<?php
require_once 'BD/Conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'eliminar':
            eliminarPago();
            break;
        case 'editar':
            editarPago();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
            break;
    }
}

function eliminarPago() {
    $idPago = $_POST['idPago'] ?? '';
    
    if (!$idPago) {
        echo json_encode(['status' => 'error', 'message' => 'ID de pago no proporcionado']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ELIMINAR_PAGO(?)");
        $stmt->bindParam(1, $idPago, PDO::PARAM_INT);
        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Pago eliminado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el pago']);
        }
    } catch (PDOException $e) {
        error_log("Error al eliminar pago: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function editarPago() {
    $idPago = $_POST['idPago'] ?? '';
    $monto = $_POST['monto'] ?? '';
    $metodoPago = $_POST['metodoPago'] ?? '';
    $estadoPago = $_POST['estadoPago'] ?? '';

    if (!$idPago || !$monto || !$metodoPago || !$estadoPago) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ACTUALIZAR_PAGO(?, ?, ?, ?)");
        $stmt->bindParam(1, $idPago, PDO::PARAM_INT);
        $stmt->bindParam(2, $monto, PDO::PARAM_STR);
        $stmt->bindParam(3, $metodoPago, PDO::PARAM_STR);
        $stmt->bindParam(4, $estadoPago, PDO::PARAM_STR);

        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Pago actualizado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el pago']);
        }
    } catch (PDOException $e) {
        error_log("Error al actualizar pago: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
