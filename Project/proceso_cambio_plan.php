<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Home.php");
    exit();
}

$nuevoPlan = $_POST['nuevo_plan'] ?? '';
if (!in_array($nuevoPlan, ['basica', 'normal', 'premium'])) {
    header("Location: gestionar_suscripcion.php");
    exit();
}

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();
    
    // Iniciar transacción
    $conn->beginTransaction();
    
    // Obtener la suscripción actual
    $stmt = $conn->prepare("SELECT IDSuscripcion FROM Suscripciones WHERE IDUsuario = ? AND EstadoSuscripcion = 'Activa'");
    $stmt->execute([$_SESSION['usuario_id']]);
    $suscripcionActual = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($suscripcionActual) {
        // Actualizar la suscripción existente a 'Cancelada'
        $stmt = $conn->prepare("UPDATE Suscripciones SET EstadoSuscripcion = 'Cancelada' WHERE IDSuscripcion = ?");
        $stmt->execute([$suscripcionActual['IDSuscripcion']]);
        
        // Crear nueva suscripción
        $stmt = $conn->prepare("CALL SP_AGREGAR_SUSCRIPCION(?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH))");
        $stmt->execute([$_SESSION['usuario_id'], ucfirst($nuevoPlan)]);
        
        // Actualizar estado del usuario
        $stmt = $conn->prepare("UPDATE Usuarios SET EstadoSuscripcion = 'Activa' WHERE IDUsuario = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        
        $conn->commit();
        
        $_SESSION['mensaje_exito'] = "Tu plan ha sido actualizado exitosamente a " . ucfirst($nuevoPlan);
        header("Location: gestionar_suscripcion.php");
        exit();
    } else {
        throw new Exception("No se encontró una suscripción activa");
    }
    
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error'] = "Error al cambiar el plan: " . $e->getMessage();
    header("Location: gestionar_suscripcion.php");
    exit();
}
