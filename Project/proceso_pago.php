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
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="proceso-pago-page">
    <header id="header">
        <div id="nav">
            <div class="topnav" id="myTopnav">
                <a href="Home.php">Inicio</a>
                <a href="BooksPage.php">Libros</a>
                <a href="likes.php">Me gusta</a>
                <a href="aboutus.php">Sobre Nosotros</a>
                <a href="contact.php">Contacto</a>
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <?php if($_SESSION['usuario_rol'] !== 'Administrador'): ?>
                        <a href="suscripciones.php">Suscribirse</a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
            </div>

            <div class="search-container">
                <input type="text" name="search" id="searchInput" placeholder="Buscar..." class="search-input">
                <a href="#" class="search-btn">
                    <i class="fas fa-search" aria-hidden="true"></i>      
                </a>
            </div>
            <div class="Container" id="containere">
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <a href="Modelo/PHP/cerrarsesion.php" class="login-btn">Cerrar sesión</a>
                <?php else: ?>
                    <a href="intranet.php" class="login-btn">Iniciar sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

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
        const form = document.getElementById('payment-form');
        const cardInput = document.getElementById('numero_tarjeta');
        const cardType = document.querySelector('.card-type');
        const cvvInput = document.getElementById('cvv');
        const expiryInput = document.getElementById('fecha_vencimiento');
        const nameInput = document.getElementById('nombre_tarjeta');

        // Formateo y validación del número de tarjeta
        cardInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Determinar tipo de tarjeta y mostrar ícono
            let icon = '';
            if (value.startsWith('4')) {
                icon = '<i class="fab fa-cc-visa" style="color: #1A1F71;"></i>';
            } else if (value.startsWith('5')) {
                icon = '<i class="fab fa-cc-mastercard" style="color: #EB001B;"></i>';
            } else if (value.startsWith('3')) {
                icon = '<i class="fab fa-cc-amex" style="color: #006FCF;"></i>';
            }
            cardType.innerHTML = icon;

            // Formatear número con espacios cada 4 dígitos
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            e.target.value = formattedValue;
        });

        // Formateo y validación de fecha de vencimiento
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Formatear como MM/YY
            if (value.length >= 2) {
                const month = value.substring(0, 2);
                const year = value.substring(2, 4);
                
                // Validar mes
                if (parseInt(month) > 12) {
                    value = '12' + year;
                } else if (parseInt(month) < 1) {
                    value = '01' + year;
                }
                
                value = month + (value.length > 2 ? '/' + year : '');
            }
            
            e.target.value = value;
        });

        // Validación del nombre del titular
        nameInput.addEventListener('input', function(e) {
            let value = e.target.value;
            let previousValue = value;
            
            // Reemplazar caracteres no permitidos
            let newValue = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]/g, '');
            newValue = newValue.toUpperCase();
            newValue = newValue.replace(/\s+/g, ' ');
            
            // Solo mostrar alerta si se intentó ingresar un carácter no permitido
            if (previousValue !== newValue && /[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s'-]/.test(previousValue)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Solo letras permitidas',
                    text: 'El nombre del titular solo debe contener letras y espacios',
                    background: '#37310D',
                    color: '#ffffff',
                    confirmButtonColor: '#730F16',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
            
            e.target.value = newValue;
        });

        // Validación de CVC
        cvvInput.addEventListener('input', function(e) {
            const cardNumber = cardInput.value.replace(/\D/g, '');
            const isAmex = cardNumber.startsWith('3');
            const maxLength = isAmex ? 4 : 3;
            
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > maxLength) {
                value = value.slice(0, maxLength);
                Swal.fire({
                    icon: 'info',
                    title: 'Longitud máxima del CVC',
                    text: `El CVC debe tener ${maxLength} dígitos para este tipo de tarjeta`,
                    background: '#37310D',
                    color: '#ffffff',
                    confirmButtonColor: '#730F16',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
            e.target.value = value;
        });

        // Validación al enviar el formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let errores = [];

            // Validar fecha de caducidad
            const expiryValue = expiryInput.value;
            const [month, year] = expiryValue.split('/');
            
            if (month && year) {
                const currentDate = new Date();
                const currentYear = currentDate.getFullYear() % 100; // Obtener últimos 2 dígitos del año actual
                const currentMonth = currentDate.getMonth() + 1; // getMonth() devuelve 0-11
                
                const expYear = parseInt(year);
                const expMonth = parseInt(month);
                
                // Validar que la fecha no esté expirada
                if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
                    errores.push('La tarjeta ha expirado. Por favor, use una tarjeta válida.');
                }
                
                // Validar que la fecha no esté muy lejos en el futuro (típicamente 5-10 años)
                if (expYear > currentYear + 10) {
                    errores.push('Fecha de expiración inválida. La fecha está muy lejos en el futuro.');
                }
            }

            // Validar nombre
            const nombre = nameInput.value.trim();
            if (nombre.length < 5 || nombre.split(' ').length < 2) {
                errores.push("Por favor, ingrese nombre y apellido completos");
            }

            // Validar CVC
            const cardNumber = document.getElementById('numero_tarjeta').value;
            const isAmex = cardNumber.startsWith('3');
            const requiredCvvLength = isAmex ? 4 : 3;
            const cvv = cvvInput.value;
            
            if (cvv.length !== requiredCvvLength) {
                errores.push(`El CVC debe tener ${requiredCvvLength} dígitos`);
            }

            if (errores.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    html: errores.join('<br>'),
                    background: '#37310D',
                    color: '#ffffff',
                    confirmButtonColor: '#730F16',
                    showConfirmButton: true,
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // Si todo está correcto, mostrar mensaje de procesamiento
            Swal.fire({
                title: 'Procesando pago',
                html: 'Por favor, espere un momento...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                background: '#37310D',
                color: '#ffffff'
            });

            // Simular procesamiento y enviar
            setTimeout(() => {
                form.submit();
            }, 2000);
        });
    });
    </script>
</body>
</html>
