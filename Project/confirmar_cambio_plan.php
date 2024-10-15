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

    // Actualizar la suscripción existente
    $stmt = $conn->prepare("UPDATE Suscripciones SET TipoSuscripcion = ?, FechaFin = DATE_ADD(CURDATE(), INTERVAL 1 MONTH) WHERE IDUsuario = ? AND EstadoSuscripcion = 'Activa'");
    $stmt->execute([ucfirst($nuevoPlan), $_SESSION['usuario_id']]);

    // Insertar nuevo pago
    $stmt = $conn->prepare("INSERT INTO Pagos (IDUsuario, IDSuscripcion, Monto, FechaPago, MetodoPago, EstadoPago) VALUES (?, (SELECT IDSuscripcion FROM Suscripciones WHERE IDUsuario = ? ORDER BY FechaInicio DESC LIMIT 1), ?, CURDATE(), 'Tarjeta de crédito', 'Completado')");
    $stmt->execute([$_SESSION['usuario_id'], $_SESSION['usuario_id'], $precio]);

    // Confirmar la transacción
    $conn->commit();

    $_SESSION['mensaje_exito'] = "Tu plan ha sido actualizado a " . ucfirst($nuevoPlan) . " exitosamente.";
    unset($_SESSION['cambio_plan']); // Limpiar la información de cambio de plan
    
    // Redirigir a Home.php con un parámetro de éxito
    header("Location: Home.php?success=true");
    exit();
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollBack();
    error_log("Error en el cambio de plan: " . $e->getMessage());
    $_SESSION['error'] = "Hubo un error al procesar tu cambio de plan. Por favor, intenta de nuevo más tarde.";
    header("Location: gestionar_suscripcion.php");
    exit();
}
