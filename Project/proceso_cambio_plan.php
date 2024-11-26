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
    
    // Definir precios de los planes
    $precios = [
        'basica' => 9.99,
        'normal' => 14.99,
        'premium' => 19.99
    ];
    
    // Obtener la suscripción actual
    $stmt = $conn->prepare("
        SELECT s.*, p.Monto as MontoActual, p.IDMetodoPago
        FROM Suscripciones s 
        LEFT JOIN Pagos p ON s.IDSuscripcion = p.IDSuscripcion 
        WHERE s.IDUsuario = ? AND s.EstadoSuscripcion = 'Activa'
        ORDER BY p.FechaPago DESC LIMIT 1
    ");
    $stmt->execute([$_SESSION['usuario_id']]);
    $suscripcionActual = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Calcular días restantes del período actual
    $fechaFin = new DateTime($suscripcionActual['FechaFin']);
    $hoy = new DateTime();
    $diasRestantes = $fechaFin->diff($hoy)->days;
    
    $precioNuevo = $precios[$nuevoPlan];
    $precioActual = $precios[strtolower($suscripcionActual['TipoSuscripcion'])];
    
    // Iniciar transacción
    $conn->beginTransaction();
    
    if ($precioNuevo > $precioActual) {
        // Calcular el monto proporcional a pagar
        $diferenciaDiaria = ($precioNuevo - $precioActual) / 30;
        $montoPagar = $diferenciaDiaria * $diasRestantes;
        $montoPagar = round($montoPagar, 2);
        
        // Registrar el pago adicional
        $stmt = $conn->prepare("
            INSERT INTO Pagos (IDUsuario, IDSuscripcion, IDMetodoPago, Monto, FechaPago, EstadoPago) 
            VALUES (?, ?, ?, ?, CURDATE(), 'Completado')
        ");
        $stmt->execute([
            $_SESSION['usuario_id'],
            $suscripcionActual['IDSuscripcion'],
            $suscripcionActual['IDMetodoPago'],
            $montoPagar
        ]);
    }
    
    // Actualizar la suscripción actual
    $stmt = $conn->prepare("
        UPDATE Suscripciones 
        SET TipoSuscripcion = ?, 
            FechaModificacion = CURDATE(),
            PrecioMensual = ?
        WHERE IDSuscripcion = ?
    ");
    $stmt->execute([
        ucfirst($nuevoPlan),
        $precioNuevo,
        $suscripcionActual['IDSuscripcion']
    ]);
    
    $conn->commit();
    
    if ($precioNuevo > $precioActual) {
        $_SESSION['mensaje_exito'] = "Plan actualizado a " . ucfirst($nuevoPlan) . ". Se ha cobrado un cargo adicional de $" . 
            number_format($montoPagar, 2) . " por la diferencia del período actual.";
    } else {
        $_SESSION['mensaje_exito'] = "Tu plan ha sido actualizado a " . ucfirst($nuevoPlan) . 
            ". El nuevo precio se aplicará en tu próxima facturación.";
    }
    
} catch (Exception $e) {
    $conn->rollBack();
    error_log("Error en cambio de plan: " . $e->getMessage());
    $_SESSION['error'] = "Error al cambiar el plan: " . $e->getMessage();
}

header("Location: gestionar_suscripcion.php");
exit();
