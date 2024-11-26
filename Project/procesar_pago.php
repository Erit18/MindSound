<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Home.php");
    exit();
}

$plan = $_POST['plan'] ?? '';
$precio = $_POST['precio'] ?? 0;

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();
    
    // Iniciar transacción
    $conn->beginTransaction();
    
    // Convertir el plan a formato correcto
    $tipoSuscripcion = ucfirst($plan);
    
    // Insertar método de pago
    $stmt = $conn->prepare("INSERT INTO MetodosPago (NombreMetodo) VALUES (?) ON DUPLICATE KEY UPDATE IDMetodoPago=LAST_INSERT_ID(IDMetodoPago)");
    $stmt->execute(['Tarjeta de crédito']);
    $idMetodoPago = $conn->lastInsertId();
    
    // Insertar nueva suscripción
    $stmt = $conn->prepare("CALL SP_AGREGAR_SUSCRIPCION(?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH))");
    $stmt->execute([$_SESSION['usuario_id'], $tipoSuscripcion]);
    
    // Actualizar estado de usuario
    $stmt = $conn->prepare("UPDATE Usuarios SET EstadoSuscripcion = 'Activa' WHERE IDUsuario = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    
    $conn->commit();
    
    $_SESSION['mensaje_exito'] = "¡Te has suscrito exitosamente al plan " . ucfirst($plan) . "!";
    header("Location: Home.php");
    exit();
    
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error'] = "Hubo un error al procesar tu pago. Por favor, intenta de nuevo.";
    header("Location: suscripciones.php");
    exit();
}
?> 