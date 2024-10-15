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

$precios = [
    'basica' => 9.99,
    'normal' => 14.99,
    'premium' => 19.99
];

$_SESSION['cambio_plan'] = [
    'plan' => $nuevoPlan,
    'precio' => $precios[$nuevoPlan]
];

// Redirigir a la página de pago para el cambio de plan
header("Location: pago_cambio_plan.php");
exit();
