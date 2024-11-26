<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: intranet.php");
    exit();
}

$plan = $_GET['plan'] ?? '';
if (!in_array($plan, ['basica', 'normal', 'premium'])) {
    header("Location: suscripciones.php");
    exit();
}

$precios = [
    'basica' => 9.99,
    'normal' => 14.99,
    'premium' => 19.99
];

$precio = $precios[$plan];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago de Suscripción - MindSound</title>
    <link rel="stylesheet" href="style/Style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo/logo.ico">
</head>
<body class="proceso-pago-page">
    <div class="payment-form-container">
        <h1>Completar Pago</h1>
        <div class="monto-a-pagar">
            Plan seleccionado: <?php echo ucfirst($plan); ?><br>
            Precio: $<?php echo number_format($precio, 2); ?>/mes
        </div>

        <form id="payment-form" action="procesar_pago.php" method="POST">
            <input type="hidden" name="plan" value="<?php echo htmlspecialchars($plan); ?>">
            <input type="hidden" name="precio" value="<?php echo htmlspecialchars($precio); ?>">
            
            <div class="form-group">
                <label for="numero_tarjeta">Número de Tarjeta</label>
                <input type="text" id="numero_tarjeta" name="numero_tarjeta" required 
                       pattern="\d{16}" maxlength="16" placeholder="1234 5678 9012 3456">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                    <input type="text" id="fecha_vencimiento" name="fecha_vencimiento" required 
                           pattern="\d{2}/\d{2}" maxlength="5" placeholder="MM/YY">
                </div>

                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" required 
                           pattern="\d{3,4}" maxlength="4" placeholder="123">
                </div>
            </div>

            <div class="form-group">
                <label for="nombre_tarjeta">Nombre en la Tarjeta</label>
                <input type="text" id="nombre_tarjeta" name="nombre_tarjeta" required 
                       placeholder="Como aparece en la tarjeta">
            </div>

            <button type="submit" class="btn-pagar">Pagar y Suscribirse</button>
        </form>
    </div>

    <script>
        // Formateo de número de tarjeta
        document.getElementById('numero_tarjeta').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });

        // Formateo de fecha de vencimiento
        document.getElementById('fecha_vencimiento').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0,2) + '/' + value.slice(2);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
