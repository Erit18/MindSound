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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Me gusta</title>
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

<!-- ____________________________________________ HEADER _______________________________________________________ -->

<!-- ____________________________________________ LIKED BOOKS _______________________________________________________ -->

<h1>Libros que me gustan</h1>
<div id="container"></div>
<div id="messageContainer"> </div>
 
<!-- ____________________________________________ LIKED BOOKS _______________________________________________________ --> 

<!-- ____________________________________________ FOOTER _______________________________________________________ -->
    
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

<!-- ____________________________________________ FOOTER _______________________________________________________ -->

<script src="script/like.js"></script>
<script>
  const modeToggle = document.getElementById('mode-toggle');
  const body = document.body;
  modeToggle.addEventListener('click', () => {
  body.classList.toggle('white-mode');
  });
</script>

</body>
</html>