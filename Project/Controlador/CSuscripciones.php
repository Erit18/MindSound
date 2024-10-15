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
            eliminarSuscripcion();
            break;
        case 'editar':
            editarSuscripcion();
            break;
        case 'agregar':
            agregarSuscripcion();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['accion'] ?? '';

    if ($accion === 'obtenerUsuarios') {
        obtenerUsuarios();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
    }
}

function eliminarSuscripcion() {
    $idSuscripcion = $_POST['idSuscripcion'] ?? '';
    
    if (!$idSuscripcion) {
        echo json_encode(['status' => 'error', 'message' => 'ID de suscripción no proporcionado']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ELIMINAR_SUSCRIPCION(?)");
        $stmt->bindParam(1, $idSuscripcion, PDO::PARAM_INT);
        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Suscripción eliminada con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la suscripción']);
        }
    } catch (PDOException $e) {
        error_log("Error al eliminar suscripción: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function editarSuscripcion() {
    $idSuscripcion = $_POST['idSuscripcion'] ?? '';
    $tipoSuscripcion = $_POST['tipoSuscripcion'] ?? '';
    $fechaInicio = $_POST['fechaInicio'] ?? '';
    $fechaFin = $_POST['fechaFin'] ?? '';
    $estadoSuscripcion = $_POST['estadoSuscripcion'] ?? '';

    if (!$idSuscripcion || !$tipoSuscripcion || !$fechaInicio || !$fechaFin || !$estadoSuscripcion) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ACTUALIZAR_SUSCRIPCION(?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $idSuscripcion, PDO::PARAM_INT);
        $stmt->bindParam(2, $tipoSuscripcion, PDO::PARAM_STR);
        $stmt->bindParam(3, $fechaInicio, PDO::PARAM_STR);
        $stmt->bindParam(4, $fechaFin, PDO::PARAM_STR);
        $stmt->bindParam(5, $estadoSuscripcion, PDO::PARAM_STR);

        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Suscripción actualizada con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la suscripción']);
        }
    } catch (PDOException $e) {
        error_log("Error al actualizar suscripción: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function agregarSuscripcion() {
    $idUsuario = $_POST['idUsuario'] ?? '';
    $tipoSuscripcion = $_POST['tipoSuscripcion'] ?? '';
    $fechaInicio = $_POST['fechaInicio'] ?? '';
    $fechaFin = $_POST['fechaFin'] ?? '';

    if (!$idUsuario || !$tipoSuscripcion || !$fechaInicio || !$fechaFin) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_AGREGAR_SUSCRIPCION(?, ?, ?, ?)");
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(2, $tipoSuscripcion, PDO::PARAM_STR);
        $stmt->bindParam(3, $fechaInicio, PDO::PARAM_STR);
        $stmt->bindParam(4, $fechaFin, PDO::PARAM_STR);

        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Suscripción agregada con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo agregar la suscripción']);
        }
    } catch (PDOException $e) {
        error_log("Error al agregar suscripción: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function obtenerUsuarios() {
    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("SELECT IDUsuario, Nombre, Apellido FROM Usuarios");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'usuarios' => $usuarios]);
    } catch (PDOException $e) {
        error_log("Error al obtener usuarios: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
