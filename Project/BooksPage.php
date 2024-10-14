<?php
require_once 'Controlador/CLibros.php';

$controladorLibros = new CLibros();
$libros = $controladorLibros->obtenerLibros();
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
    <div class="book-card">
        <img src="<?php echo $libro['RutaPortada']; ?>" alt="<?php echo $libro['Titulo']; ?>" class="book-cover">
        <div class="book-info">
            <h3 class="book-title"><?php echo $libro['Titulo']; ?></h3>
            <p class="book-author"><?php echo $libro['Autor']; ?></p>
            <a href="detalleLibro.php?id=<?php echo $libro['IDLibro']; ?>" class="book-details-link">Ver detalles</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ____________________________________________ SECCIÓN DE LIBROS _______________________________________________________ -->    
  

<!-- ____________________________________________ PIE DE PÁGINA _______________________________________________________ -->
    
<footer>
  <div class="footer-content">
    <h3>Compañía</h3>
    <p>Proyecto JS
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nam, praesentium quas sint accusantium dolorem, aliquam tempora voluptates at deserunt consectetur excepturi ratione adipisci. Accusantium animi totam labore perferendis incidunt corporis!
    </p>
    <ul class="socials">
      <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
    </ul>
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
