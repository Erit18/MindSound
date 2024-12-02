<?php
require_once 'Controlador/CLibros.php';

$controladorLibros = new CLibros();
$libros = $controladorLibros->obtenerLibros();

// Añade esta línea para definir $baseUrl
$baseUrl = '/mindsound/Project';
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
  <link rel="stylesheet" href="style/BooksPage.css">
  <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
  <title>Libros</title>
</head>
  
  
<body data-user-logged-in="<?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>" 
      data-user-id="<?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario']['IDUsuario'] : ''; ?>">
   
<!-- ____________________________________________ ENCABEZADO _______________________________________________________ -->

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


<!-- ____________________________________________ ENCABEZADO _______________________________________________________ -->


  
<!-- ____________________________________________ SECCIÓN DE LIBROS _______________________________________________________ -->

<h2>Libros</h2>
<div id="container" class="books-container">
    <?php foreach ($libros as $libro): ?>
    <article class="card">
        <img class="card__background" 
             src="<?php echo file_exists($libro['RutaPortada']) ? $baseUrl . '/' . $libro['RutaPortada'] : $baseUrl . '/img/default-cover.jpg'; ?>" 
             alt="<?php echo htmlspecialchars($libro['Titulo']); ?>" 
             width="1920" height="2193">
        <div class="card__content | flow">
            <div class="card__content--container | flow">
                <h3 class="card__title"><?php echo $libro['Titulo']; ?></h3>
                <p class="card__description">
                    <?php echo substr($libro['Descripcion'], 0, 100) . '...'; ?>
                </p>
            </div>
            <a href="detalleLibro.php?id=<?php echo $libro['IDLibro']; ?>"><button class="card__button">Leer más</button></a>
        </div>
    </article>
    <?php endforeach; ?>
</div>

<!-- ____________________________________________ SECCIÓN DE LIBROS _______________________________________________________ -->    
  

<!-- ____________________________________________ PIE DE PÁGINA _______________________________________________________ -->
    
<footer>
  <div class="footer-content">
    <h3>SoundMind</h3>
    <p>SoundMind es tu destino premium para audiolibros. Ofrecemos una amplia selección de títulos gratuitos, 
    así como una colección exclusiva de audiolibros premium para nuestros suscriptores. Nuestra misión es hacer 
    que la literatura sea accesible para todos, en cualquier momento y lugar. Con SoundMind, puedes sumergirte 
    en historias fascinantes, aprender nuevos conceptos y disfrutar de la narración profesional, todo desde la 
    comodidad de tu dispositivo. Únete a nuestra comunidad de amantes de la lectura auditiva y descubre un 
    nuevo mundo de posibilidades literarias.</p>
  </div>
</footer>

<!-- ____________________________________________ PIE DE PÁGINA _______________________________________________________ -->




<script src="https://hammerjs.github.io/dist/hammer.js"></script>
<script src="script/BooksPage.js"></script>
<script>
  const modeToggle = document.getElementById('mode-toggle');
  const body = document.body;
  modeToggle.addEventListener('click', () => {
  body.classList.toggle('white-mode');
  });
</script>

    

</body>
</html>
