<?php
session_start();
require_once 'CLikes.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$controladorLikes = new CLikes();
$libros = $controladorLikes->obtenerLibrosGuardados($_SESSION['usuario']['IDUsuario']);
echo json_encode($libros); 