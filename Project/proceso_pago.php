<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

// Verificar si el usuario está logueado
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

$baseUrl = '/Project'; // Ajusta esto según la estructura de tu proyecto
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=Dancing+Script:wght@700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&amp;display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo/logo.ico">
    <link rel="stylesheet" href="style/Style.css">
    <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
    <title>Proceso de Pago - MindSound</title>
</head>
<body class="proceso-pago-page">
    <!--  HEADER-->
    <header id="header">
        <div id="nav">
            
            <div class="topnav" id="myTopnav">
                <a href="Home.php">Inicio</a>
                <a href="BooksPage.php">Libros</a>
                <a href="likes.php">Me gusta</a>
                <a href="aboutus.php">Sobre Nosotros</a>
                <a href="contact.php">Contacto</a>
                <?php if(isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] !== 'Administrador'): ?>
                    <a href="suscripciones.php">Suscribirse</a>
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

    <!-- CONTENIDO PRINCIPAL -->
    <main>
        <h1>Proceso de Pago</h1>
        <form id="payment-form" action="confirmar_pago.php" method="post">
            <input type="hidden" name="plan" value="<?php echo htmlspecialchars($plan); ?>">
            <input type="hidden" name="monto" value="<?php echo $precios[$plan]; ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre en la tarjeta:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="numero_tarjeta">Número de tarjeta:</label>
                <input type="text" id="numero_tarjeta" name="numero_tarjeta" required>
            </div>
            
            <div class="form-group">
                <label for="fecha_expiracion">Fecha de expiración:</label>
                <input type="text" id="fecha_expiracion" name="fecha_expiracion" placeholder="MM/AA" required>
            </div>
            
            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
            </div>
            
            <button type="submit" class="btn-pagar">Pagar $<?php echo $precios[$plan]; ?></button>
        </form>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="footer-content">
            <h3>MindSound</h3>
            <p>Tu plataforma de audiolibros favorita</p>
            <ul class="socials">
                <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </footer>

    <script src="https://hammerjs.github.io/dist/hammer.js"></script>
    <script src="script/HomeScript.js"></script>
    <script>
        const modeToggle = document.getElementById('mode-toggle');
        const body = document.body;
        modeToggle.addEventListener('click', () => {
            body.classList.toggle('white-mode');
        });

        const root = document.documentElement;
        const marqueeElementsDisplayed = getComputedStyle(root).getPropertyValue("--marquee-elements-displayed");
        const marqueeContent = document.querySelector("ul.marquee-content");

        root.style.setProperty("--marquee-elements", marqueeContent.children.length);

        for(let i=0; i<marqueeElementsDisplayed; i++) {
            marqueeContent.appendChild(marqueeContent.children[i].cloneNode(true));
        }
    </script>
</body>
</html>
