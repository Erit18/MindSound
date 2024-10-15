<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="img/logo/logo.ico">
  <link rel="stylesheet" href="style/Style.css">
  <link rel="stylesheet" href="style/aboutus.css">
  <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
  <title>Sobre Nosotros - MindSound</title>
</head>
  
<body>

<!-- ____________________________________________ HEADER _______________________________________________________ -->
  
<header id="header">
  <div id="nav">
    <div class="topnav" id="myTopnav">
      <a href="Home.php">Inicio</a>
      <a href="BooksPage.php">Libros</a>
      <a href="likes.php">Me gusta</a>
      <a href="aboutus.php">Sobre Nosotros</a>
      <a href="contact.php">Contacto</a>
      <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>

    <div class="search-container">
      <input type="text" name="search" id="searchInput" placeholder="Buscar..." class="search-input">
      <a href="#" class="search-btn">
        <i class="fas fa-search" aria-hidden="true"></i>      
      </a>
    </div>
  </div>
</header>

<!-- ____________________________________________ ABOUTUS _______________________________________________________ -->

<main class="aboutus-container">
  <h1 class="aboutus-title">Sobre Nosotros</h1>
  <div class="aboutus-content">
    <div class="aboutus-image">
      <img src="img/logo/invidente.jpg" alt="Persona invidente usando audífonos">
    </div>
    <div class="aboutus-text">
      <h2>MindSound</h2>
      <p>Somos una plataforma web diseñada para mejorar el acceso a la lectura para personas con discapacidad visual, permitiendo la navegación y el consumo de audiolibros a través de tecnología de inteligencia artificial y control por voz.</p>
      <h3>Misión</h3>
      <p>Facilitar el acceso a la cultura y la educación a través de una plataforma inclusiva que ofrece audiolibros de calidad, con un enfoque en accesibilidad y usabilidad.</p>
      <h3>Visión</h3>
      <p>Ser la plataforma líder en audiolibros accesibles para personas ciegas en Perú, ofreciendo una experiencia intuitiva y moderna que empodere a la comunidad invidente.</p>
    </div>
  </div>
</main>

<!-- ____________________________________________ FOOTER _______________________________________________________ -->
    
<footer>
  <div class="footer-content">
    <h3>MindSound</h3>
    <p>Tu destino premium para audiolibros accesibles. Descubre un mundo de historias al alcance de tus oídos.</p>
    <ul class="socials">
      <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
    </ul>
  </div>
</footer>

<script src="script/aboutus.js"></script>
</body>
</html>
