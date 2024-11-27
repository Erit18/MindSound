<?php
require_once '../Controlador/CLibros.php';

// Obtener libros del género "Biográfico"
$controladorLibros = new CLibros();
$libros = $controladorLibros->obtenerLibrosPorGenero('Biográfico');
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=Dancing+Script:wght@700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato&amp;display=swap" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="..//img/logo/logo.ico">
 <link rel="stylesheet" href="../style/Style.css">
 <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
  <title>Biography</title>
</head>
  
  <body>

 <!-- ____________________________________________ HEADER _______________________________________________________ -->
  
 <header id="header">
  <div id="nav">

    <div class="topnav" id="myTopnav">
      <a href="../Home.php">Inicio</a> 
      <a href="../BooksPage.php">Libros</a>
      <a href="../likes.php">Me gusta</a>
      <a href="../aboutus.php">Sobre Nosotros</a>
      <a href="../contact.php">Contactos</a>
      <a href="../final cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>
    
    <div class="search-container">
      <input type="text" name="search" id="searchInput" placeholder="Search..." class="search-input">
      <a href="#" class="search-btn">
      <i class="fas fa-search" aria-hidden="true"></i>      
      </a>
    </div>
</div>
</header>

<!-- ____________________________________________ HEADER _______________________________________________________ -->


    <h1>Biography</h1>
    <div id="container">
        <?php foreach ($libros as $libro): ?>
            <div class="card">
                <img class="card__background" src="<?php echo '../' . $libro['RutaPortada']; ?>" alt="<?php echo htmlspecialchars($libro['Titulo']); ?>">
                <div class="card__content">
                    <div class="card__content--container">
                        <h3 class="card__title"><?php echo htmlspecialchars($libro['Titulo']); ?></h3>
                        <p class="card__description"><?php echo htmlspecialchars($libro['Descripcion']); ?></p>
                    </div>
                    <div class="liked_books">
                        <button class="card__button">
                            <a href="../detalleLibro.php?id=<?php echo $libro['IDLibro']; ?>">LEER MÁS</a>
                        </button>
                        <button class="like-button" data-book-id="<?php echo $libro['IDLibro']; ?>">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
  </div>



<!-- ____________________________________________ FOOTER _______________________________________________________ -->
    
<footer>
  <div class="footer-content">
    <h3>ITI Books</h3>
    <p>JS Project
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

<!-- ____________________________________________ FOOTER _______________________________________________________ -->




<script src="https://hammerjs.github.io/dist/hammer.js"></script>
<script src="../script/like.js"></script>
<script>
  const modeToggle = document.getElementById('mode-toggle');
  const body = document.body;
  modeToggle.addEventListener('click', () => {
  body.classList.toggle('white-mode');
  });
</script>

    

</body>
</html>

