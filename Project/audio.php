<?php
session_start();
require_once 'Controlador/CLibros.php';

$idLibro = $_GET['id'] ?? 0;
$controladorLibros = new CLibros();
$libro = $controladorLibros->obtenerLibroPorId($idLibro);

if (!$libro) {
    header("Location: BooksPage.php");
    exit();
}

$baseUrl = '/Project'; // Ajusta esto según la estructura de tu proyecto

// Función para verificar el estado de suscripción
function tieneSubscripcionActiva($userId) {
    // Implementación de la función
}
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
  <link rel="stylesheet" href="style/AudioStyle.css">
  <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
  <title><?php echo htmlspecialchars($libro['Titulo']); ?> - Audio</title>
</head>
  
<body>

<!-- ____________________________________________ ENCABEZADO _______________________________________________________ -->
  
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
          <?php if(tieneSubscripcionActiva($_SESSION['usuario_id'])): ?>
            <a href="gestionar_suscripcion.php">Gestionar Suscripción</a>
          <?php else: ?>
            <a href="suscripciones.php">Suscribirse</a>
          <?php endif; ?>
        <?php endif; ?>
      <?php else: ?>
        <a href="intranet.php?redirect=suscripciones.php">Suscribirse</a>
      <?php endif; ?>
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

<!-- ____________________________________________ ENCABEZADO _______________________________________________________ -->

<!-- ____________________________________________ SECCIÓN DE AUDIO _______________________________________________________ -->
  
    <audio id="audio" src="<?php echo $baseUrl . '/' . $libro['RutaAudio']; ?>"></audio>
    <div class="audioPlayer">
        <h1 class="audioName" id="AudioName"><?php echo htmlspecialchars($libro['Titulo']); ?></h1>
        <p class="authorName" id="AuthorName"><?php echo htmlspecialchars($libro['Autor']); ?></p>
        <div class="disk" style="background-image: url(<?php echo $baseUrl . '/' . $libro['RutaPortada']; ?>);"></div>
        <div class="audioSlider">
            <input type="range" value="0" class="bar" min="0" max="100" step="0.1">
            <span class="timeNow">00:00</span>
            <span class="audioDuration">00:00</span>
        </div>
        <div class="controls">
          <button class="btn backwardBtn"><img src="img/pre.png" alt=""> </button>
          <button class="playBtn pause">
            <span></span> 
            <span></span>
          </button>
          <button class="btn forwardBtn"><img src="img/nxt.png" alt=""> </button>
        </div>
    </div>

<!-- ____________________________________________ SECCIÓN DE AUDIO _______________________________________________________ -->

    <script src="https://hammerjs.github.io/dist/hammer.js"></script>
    <script src="script/script.js"></script>
    <script src="script/audioPlayer.js"></script>
    <script>
      const modeToggle = document.getElementById('mode-toggle');
      const body = document.body;
      modeToggle.addEventListener('click', () => {
      body.classList.toggle('white-mode');
      });
    </script>

</body>
</html>
