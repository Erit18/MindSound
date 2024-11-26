<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Home.php");
    exit();
}

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();
    
    // Iniciar transacción
    $conn->beginTransaction();
    
    // Obtener la suscripción activa
    $stmt = $conn->prepare("SELECT IDSuscripcion FROM Suscripciones WHERE IDUsuario = ? AND EstadoSuscripcion = 'Activa'");
    $stmt->execute([$_SESSION['usuario_id']]);
    $suscripcion = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($suscripcion) {
        // Actualizar la suscripción a 'Cancelada'
        $stmt = $conn->prepare("UPDATE Suscripciones SET EstadoSuscripcion = 'Cancelada' WHERE IDSuscripcion = ?");
        $stmt->execute([$suscripcion['IDSuscripcion']]);
        
        // Actualizar el estado del usuario
        $stmt = $conn->prepare("UPDATE Usuarios SET EstadoSuscripcion = 'Inactiva' WHERE IDUsuario = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        
        $conn->commit();
        
        $_SESSION['mensaje_exito'] = "Tu suscripción ha sido cancelada. Seguirás teniendo acceso hasta el final del período actual.";
    } else {
        throw new Exception("No se encontró una suscripción activa");
    }
    
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error'] = "Error al cancelar la suscripción: " . $e->getMessage();
}

header("Location: gestionar_suscripcion.php");
exit();
?> 