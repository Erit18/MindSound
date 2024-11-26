<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Home.php");
    exit();
}

$plan = $_POST['plan'] ?? '';
$precio = $_POST['precio'] ?? 0;
$numero_tarjeta = $_POST['numero_tarjeta'] ?? '';
$fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$nombre_tarjeta = $_POST['nombre_tarjeta'] ?? '';

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();
    
    // Iniciar transacción
    $conn->beginTransaction();
    
    // 1. Insertar método de pago
    $stmt = $conn->prepare("INSERT INTO MetodosPago (NombreMetodo) VALUES (?) ON DUPLICATE KEY UPDATE IDMetodoPago=LAST_INSERT_ID(IDMetodoPago)");
    $stmt->execute(['Tarjeta de crédito']);
    $idMetodoPago = $conn->lastInsertId();
    
    // 2. Crear nueva suscripción
    $stmt = $conn->prepare("CALL SP_AGREGAR_SUSCRIPCION(?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH))");
    $stmt->execute([$_SESSION['usuario_id'], ucfirst($plan)]);
    
    // 3. Obtener el ID de la suscripción recién creada
    $stmt = $conn->prepare("SELECT IDSuscripcion FROM Suscripciones WHERE IDUsuario = ? ORDER BY FechaInicio DESC LIMIT 1");
    $stmt->execute([$_SESSION['usuario_id']]);
    $suscripcion = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 4. Registrar el pago
    $stmt = $conn->prepare("
        INSERT INTO Pagos (IDUsuario, IDSuscripcion, IDMetodoPago, Monto, FechaPago, EstadoPago) 
        VALUES (?, ?, ?, ?, CURDATE(), 'Completado')
    ");
    $stmt->execute([
        $_SESSION['usuario_id'],
        $suscripcion['IDSuscripcion'],
        $idMetodoPago,
        $precio
    ]);
    
    // 5. Actualizar estado del usuario
    $stmt = $conn->prepare("UPDATE Usuarios SET EstadoSuscripcion = 'Activa' WHERE IDUsuario = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    
    $conn->commit();
    
    $_SESSION['mensaje_exito'] = "¡Te has suscrito exitosamente al plan " . ucfirst($plan) . "!";
    header("Location: Home.php");
    exit();
    
} catch (Exception $e) {
    $conn->rollBack();
    error_log("Error en suscripción: " . $e->getMessage());
    $_SESSION['error'] = "Hubo un error al procesar tu pago. Por favor, intenta de nuevo.";
    header("Location: suscripciones.php");
    exit();
}
?> 