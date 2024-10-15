<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: intranet.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: suscripciones.php");
    exit();
}

$plan = $_POST['plan'] ?? '';
$monto = $_POST['monto'] ?? 0;

if (!in_array($plan, ['basica', 'normal', 'premium']) || $monto <= 0) {
    header("Location: suscripciones.php");
    exit();
}

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();

    // Iniciar transacción
    $conn->beginTransaction();

    // Insertar nueva suscripción
    $stmt = $conn->prepare("CALL SP_AGREGAR_SUSCRIPCION(?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH))");
    $stmt->bindParam(1, $_SESSION['usuario_id'], PDO::PARAM_INT);
    $tipoSuscripcion = ucfirst($plan); // Convertir primera letra a mayúscula
    $stmt->bindParam(2, $tipoSuscripcion, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->closeCursor(); // Cerrar el cursor después de la ejecución

    // Obtener el ID de la suscripción recién insertada
    $stmt = $conn->query("SELECT LAST_INSERT_ID()");
    $idSuscripcion = $stmt->fetchColumn();
    $stmt->closeCursor(); // Cerrar el cursor después de obtener el resultado

    if (!$idSuscripcion) {
        throw new Exception("No se pudo obtener el ID de la suscripción");
    }

    // Insertar nuevo pago
    $stmt = $conn->prepare("CALL SP_AGREGAR_PAGO(?, ?, ?, 'Tarjeta de crédito', 'Completado')");
    $stmt->bindParam(1, $_SESSION['usuario_id'], PDO::PARAM_INT);
    $stmt->bindParam(2, $idSuscripcion, PDO::PARAM_INT);
    $stmt->bindParam(3, $monto, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->closeCursor(); // Cerrar el cursor después de la ejecución

    // Actualizar el estado de suscripción del usuario
    $stmt = $conn->prepare("UPDATE Usuarios SET EstadoSuscripcion = 'Activa' WHERE IDUsuario = ?");
    $stmt->bindParam(1, $_SESSION['usuario_id'], PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor(); // Cerrar el cursor después de la ejecución

    // Confirmar la transacción
    $conn->commit();

    $_SESSION['mensaje'] = "¡Suscripción realizada con éxito!";
    header("Location: Home.php");
    exit();
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("Error en la suscripción: " . $e->getMessage());
    $_SESSION['error'] = "Hubo un error al procesar tu suscripción. Por favor, intenta de nuevo más tarde.";
    header("Location: suscripciones.php");
    exit();
}
