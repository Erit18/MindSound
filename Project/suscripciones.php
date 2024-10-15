<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: intranet.php");
    exit();
}

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
    <title>Suscripciones - MindSound</title>
</head>
<body class="suscripciones-page">
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
        <h1>Elige tu plan de suscripción</h1>
        <div class="subscription-plans">
            <div class="plan">
                <h2>Básica</h2>
                <p class="price">$9.99/mes</p>
                <ul>
                    <li>Acceso a libros gratuitos</li>
                    <li>1 libro premium al mes</li>
                    <li>Soporte por correo</li>
                </ul>
                <a href="proceso_pago.php?plan=basica" class="btn-subscribe">Suscribirse</a>
            </div>
            <div class="plan">
                <h2>Normal</h2>
                <p class="price">$14.99/mes</p>
                <ul>
                    <li>Acceso a libros gratuitos</li>
                    <li>3 libros premium al mes</li>
                    <li>Soporte prioritario</li>
                </ul>
                <a href="proceso_pago.php?plan=normal" class="btn-subscribe">Suscribirse</a>
            </div>
            <div class="plan">
                <h2>Premium</h2>
                <p class="price">$19.99/mes</p>
                <ul>
                    <li>Acceso ilimitado a todos los libros</li>
                    <li>Estrenos anticipados</li>
                    <li>Soporte 24/7</li>
                </ul>
                <a href="proceso_pago.php?plan=premium" class="btn-subscribe">Suscribirse</a>
            </div>
        </div>
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
