<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Home.php");
    exit();
}

$nuevoPlan = $_POST['nuevo_plan'] ?? '';
$precio = $_POST['precio'] ?? 0;

if (!in_array($nuevoPlan, ['basica', 'normal', 'premium']) || $precio <= 0) {
    header("Location: gestionar_suscripcion.php");
    exit();
}

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();
    
    // Iniciar transacción
    $conn->beginTransaction();
    
    // Agregar suscripción usando SP_AGREGAR_SUSCRIPCION
    $stmt = $conn->prepare("CALL SP_AGREGAR_SUSCRIPCION(?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH))");
    $stmt->execute([$_SESSION['usuario_id'], ucfirst($nuevoPlan)]);
    
    // Obtener el ID de la suscripción recién creada
    $idSuscripcion = $conn->lastInsertId();
    
    // Agregar pago usando SP_AGREGAR_PAGO
    $stmt = $conn->prepare("CALL SP_AGREGAR_PAGO(?, ?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['usuario_id'],
        $idSuscripcion,
        $precio,
        1, // ID del método de pago por defecto
        'Completado'
    ]);
    
    $conn->commit();

    $_SESSION['mensaje_exito'] = "Tu plan ha sido actualizado a " . ucfirst($nuevoPlan) . " exitosamente.";
    unset($_SESSION['cambio_plan']); // Limpiar la información de cambio de plan
    
    // Redirigir a Home.php con un parámetro de éxito
    header("Location: Home.php?success=true");
    exit();
} catch (Exception $e) {
    $conn->rollBack();
    error_log("Error en el cambio de plan: " . $e->getMessage());
    $_SESSION['error'] = "Hubo un error al procesar tu cambio de plan. Por favor, intenta de nuevo más tarde.";
    header("Location: gestionar_suscripcion.php");
    exit();
}
