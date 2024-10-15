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

// Aquí iría la lógica para actualizar la suscripción en la base de datos
// y redirigir al usuario a una página de pago si es necesario

// Por ahora, simplemente redirigimos de vuelta a la página de gestión de suscripción
$_SESSION['mensaje_exito'] = "Tu plan ha sido actualizado a " . ucfirst($nuevoPlan) . ".";
header("Location: gestionar_suscripcion.php");
exit();
