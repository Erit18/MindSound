<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=Dancing+Script:wght@700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato&amp;display=swap" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="img/logo/logo.ico">
  <link rel="stylesheet" href="style/contact.css">
  <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
  <title>Contacto</title>
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

<div id="main">
  
<!-- ____________________________________________ SECCIÓN DE CONTACTO _______________________________________________________ -->

  <div class="container">
    <div class="form">
        <div class="contact-info">
            <h2>¿Necesitas ayuda?</h2>
            <p>
                Estamos aquí para ayudarte.
                Contáctanos para cualquier consulta
                sobre libros, pedidos o sugerencias. Te
                responderemos lo antes posible.
            </p>
            
            <div class="information">
                <i class="fas fa-map-marker-alt"></i>
                <p>Calle Librería 123, Madrid, España</p>
            </div>
            
            <div class="information">
                <i class="fas fa-envelope"></i>
                <p>contacto@libreria.com</p>
            </div>
            
            <div class="information">
                <i class="fas fa-phone"></i>
                <p>+34 912 345 678</p>
            </div>

            <div class="social-media">
                <p>Síguenos en redes sociales:</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <h2>Envíanos un mensaje</h2>
            <form action="process_contact.php" method="POST">
                <div class="input-container">
                    <input type="text" name="name" class="input" placeholder="Nombre" required>
                </div>
                
                <div class="input-container">
                    <input type="email" name="email" class="input" placeholder="Correo electrónico" required>
                </div>
                
                <div class="input-container">
                    <input type="tel" name="phone" class="input" placeholder="Teléfono" required>
                </div>
                
                <div class="input-container">
                    <textarea name="message" class="input" placeholder="Mensaje" required></textarea>
                </div>
                
                <button type="submit" class="btn">Enviar</button>
            </form>
        </div>
    </div>
  </div>

<!-- ____________________________________________ SECCIÓN DE CONTACTO _______________________________________________________ -->

</div>

<!-- ____________________________________________ PIE DE PÁGINA _______________________________________________________ -->
    
<footer>
  <div class="footer-content">
    <h3>empresa</h3>
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
<script src="script/contact.js"></script>
<script>
  const modeToggle = document.getElementById('mode-toggle');
  const body = document.body;
  modeToggle.addEventListener('click', () => {
  body.classList.toggle('white-mode');
  });
</script>

</body>
</html>
