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
            eliminarGenero();
            break;
        case 'editar':
            editarGenero();
            break;
        case 'agregar':
            agregarGenero();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
            break;
    }
}

function eliminarGenero() {
    $idGenero = $_POST['idGenero'] ?? '';
    
    if (!$idGenero) {
        echo json_encode(['status' => 'error', 'message' => 'ID de género no proporcionado']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ELIMINAR_GENERO(?)");
        $stmt->bindParam(1, $idGenero, PDO::PARAM_INT);
        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Género eliminado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el género']);
        }
    } catch (PDOException $e) {
        error_log("Error al eliminar género: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function editarGenero() {
    $idGenero = $_POST['idGenero'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    if (!$idGenero || !$nombre || !$descripcion) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ACTUALIZAR_GENERO(?, ?, ?)");
        $stmt->bindParam(1, $idGenero, PDO::PARAM_INT);
        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(3, $descripcion, PDO::PARAM_STR);

        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Género actualizado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el género']);
        }
    } catch (PDOException $e) {
        error_log("Error al actualizar género: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function agregarGenero() {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    if (!$nombre || !$descripcion) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_INSERTAR_GENERO(?, ?)");
        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);

        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Género agregado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo agregar el género']);
        }
    } catch (PDOException $e) {
        error_log("Error al agregar género: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
