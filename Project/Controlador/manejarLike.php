<?php
session_start();
require_once 'CLikes.php';

header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

// Obtener y decodificar los datos enviados
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['bookId']) || !isset($data['action'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

$controladorLikes = new CLikes();
$userId = $_SESSION['usuario']['IDUsuario'];
$bookId = $data['bookId'];
$action = $data['action'];

try {
    if ($action === 'like') {
        $result = $controladorLikes->guardarLibro($userId, $bookId);
    } else {
        $result = $controladorLikes->eliminarLibroGuardado($userId, $bookId);
    }
    
    echo json_encode(['success' => $result]);
} catch (Exception $e) {
    error_log("Error en manejarLike.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor']);
} 