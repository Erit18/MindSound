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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="proceso-pago-page">
    <div class="payment-container">
        <h1>Completar Pago</h1>
        
        <div class="plan-info">
            <p class="plan-selected">Plan seleccionado: <?php echo ucfirst($plan); ?></p>
            <p class="plan-price">Precio: $<?php echo number_format($precio, 2); ?>/mes</p>
        </div>

        <div class="card-form">
            <h2>Información de Pago</h2>
            
            <form id="payment-form" action="procesar_pago.php" method="POST">
                <input type="hidden" name="plan" value="<?php echo htmlspecialchars($plan); ?>">
                <input type="hidden" name="precio" value="<?php echo htmlspecialchars($precio); ?>">
                
                <div class="form-group">
                    <label for="numero_tarjeta">Número de tarjeta</label>
                    <div class="card-input-container">
                        <input type="text" 
                               id="numero_tarjeta" 
                               name="numero_tarjeta" 
                               placeholder="1234 5678 9012 3456" 
                               required
                               maxlength="19">
                        <div class="card-type"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_vencimiento">Fecha de caducidad</label>
                        <input type="text" 
                               id="fecha_vencimiento" 
                               name="fecha_vencimiento" 
                               placeholder="MM/YY" 
                               maxlength="5"
                               required
                               pattern="(0[1-9]|1[0-2])\/([0-9]{2})"
                               title="Por favor ingrese una fecha válida en formato MM/YY">
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVC</label>
                        <input type="text" 
                               id="cvv" 
                               name="cvv" 
                               placeholder="123" 
                               required
                               maxlength="4"
                               pattern="[0-9]{3,4}"
                               title="Ingrese un CVC válido (3 o 4 dígitos)">
                    </div>
                </div>

                <div class="form-group">
                    <label for="nombre_tarjeta">Nombre del titular</label>
                    <input type="text" 
                           id="nombre_tarjeta" 
                           name="nombre_tarjeta" 
                           placeholder="Como aparece en la tarjeta" 
                           required
                           pattern="[A-ZÁÉÍÓÚÑa-záéíóúñ\s'-]+"
                           title="Ingrese el nombre como aparece en la tarjeta (solo letras y espacios)">
                </div>

                <button type="submit" class="btn-pagar">
                    Pagar <?php echo number_format($precio, 2); ?> USD
                </button>
            </form>
        </div>
    </div>

    <style>
    .payment-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
    }

    .plan-info {
        text-align: center;
        margin-bottom: 30px;
    }

    .plan-selected {
        color: #8F9569;
        font-size: 1.2em;
        margin-bottom: 5px;
    }

    .plan-price {
        color: #8F9569;
        font-size: 1.1em;
    }

    .card-form {
        background-color: #37310D;
        padding: 30px;
        border-radius: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .card-input-container {
        position: relative;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #8F9569;
    }

    input {
        width: 100%;
        padding: 12px;
        background-color: #22312A;
        border: 1px solid #8F9569;
        border-radius: 5px;
        color: #ffffff;
        font-size: 16px;
    }

    input::placeholder {
        color: #8F9569;
        opacity: 0.7;
    }

    .form-row {
        display: flex;
        gap: 20px;
    }

    .form-row .form-group {
        flex: 1;
    }

    .btn-pagar {
        width: 100%;
        padding: 15px;
        background-color: #730F16;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-pagar:hover {
        background-color: #8B1319;
    }

    .card-type {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 24px;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cardInput = document.getElementById('numero_tarjeta');
        const cardType = document.querySelector('.card-type');

        cardInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Determinar tipo de tarjeta y longitud máxima
            let maxLength = 16;
            let icon = '';
            
            if (value.startsWith('4')) { // Visa
                maxLength = 16;
                icon = '<i class="fab fa-cc-visa" style="color: #1A1F71;"></i>';
            } else if (value.startsWith('5')) { // Mastercard
                maxLength = 16;
                icon = '<i class="fab fa-cc-mastercard" style="color: #EB001B;"></i>';
            } else if (value.startsWith('3')) { // American Express
                maxLength = 15;
                icon = '<i class="fab fa-cc-amex" style="color: #006FCF;"></i>';
            }

            // Limitar la longitud según el tipo de tarjeta
            value = value.substring(0, maxLength);
            
            // Formatear con espacios
            let formattedValue = '';
            for(let i = 0; i < value.length; i++) {
                if(i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            e.target.value = formattedValue;
            cardType.innerHTML = icon;

            // Actualizar el maxLength del input considerando los espacios
            const spacesCount = Math.floor((maxLength - 1) / 4);
            e.target.setAttribute('maxlength', maxLength + spacesCount);
        });

        // Validación adicional en el formulario
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            const cardNumber = cardInput.value.replace(/\D/g, '');
            let isValid = false;

            // Validar longitud según tipo de tarjeta
            if (cardNumber.startsWith('4')) { // Visa
                isValid = cardNumber.length === 16;
            } else if (cardNumber.startsWith('5')) { // Mastercard
                isValid = cardNumber.length === 16;
            } else if (cardNumber.startsWith('3')) { // American Express
                isValid = cardNumber.length === 15;
            }

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, ingrese un número de tarjeta válido con la longitud correcta.');
                cardInput.focus();
            }
        });

        // Validación de fecha de caducidad
        const expiryInput = document.getElementById('fecha_vencimiento');
        expiryInput.setAttribute('maxlength', 5);

        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Limitar a 4 dígitos (MMYY)
            value = value.substring(0, 4);
            
            // Formatear como MM/YY
            if (value.length >= 2) {
                const month = parseInt(value.substring(0, 2));
                // Validar que el mes esté entre 01 y 12
                if (month > 12) {
                    value = '12' + value.substring(2);
                } else if (month < 1) {
                    value = '01' + value.substring(2);
                }
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            
            e.target.value = value;
        });

        // Validación al enviar el formulario
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            const expiryValue = expiryInput.value;
            const [month, year] = expiryValue.split('/');
            
            if (month && year) {
                const currentDate = new Date();
                const currentYear = currentDate.getFullYear() % 100; // Obtener últimos 2 dígitos del año
                const currentMonth = currentDate.getMonth() + 1; // getMonth() devuelve 0-11
                
                const expYear = parseInt(year);
                const expMonth = parseInt(month);
                
                // Validar que la fecha no esté expirada
                if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
                    e.preventDefault();
                    alert('La tarjeta ha expirado. Por favor, use una tarjeta válida.');
                    expiryInput.focus();
                    return;
                }
                
                // Validar que la fecha no esté muy lejos en el futuro (típicamente las tarjetas son válidas por 5 años)
                if (expYear > currentYear + 5) {
                    e.preventDefault();
                    alert('Fecha de expiración inválida. Por favor, verifique la fecha.');
                    expiryInput.focus();
                    return;
                }
            }
        });

        // Validación de CVC
        const cvvInput = document.getElementById('cvv');
        cvvInput.addEventListener('input', function(e) {
            // Solo permitir números
            let value = e.target.value.replace(/\D/g, '');
            
            // Obtener el tipo de tarjeta para determinar la longitud del CVC
            const cardNumber = document.getElementById('numero_tarjeta').value;
            const isAmex = cardNumber.startsWith('3');
            const maxLength = isAmex ? 4 : 3;
            
            // Limitar la longitud según el tipo de tarjeta
            value = value.substring(0, maxLength);
            e.target.value = value;
        });

        // Validación del nombre del titular
        const nameInput = document.getElementById('nombre_tarjeta');
        nameInput.addEventListener('input', function(e) {
            // Solo permitir letras, espacios y algunos caracteres especiales comunes en nombres
            let value = e.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]/g, '');
            
            // Convertir a mayúsculas
            value = value.toUpperCase();
            
            // Evitar espacios dobles
            value = value.replace(/\s+/g, ' ');
            
            e.target.value = value;
        });

        // Actualizar el HTML para los inputs
        document.getElementById('cvv').setAttribute('maxlength', '4');
        document.getElementById('cvv').setAttribute('pattern', '[0-9]{3,4}');
        document.getElementById('cvv').setAttribute('title', 'Ingrese un CVC válido (3 o 4 dígitos)');
    });
    </script>
</body>
</html>
