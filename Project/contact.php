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
    <div id="logo">
      <a href="Home.php"><img src="img/logo/logo.png" alt="test image" class="responsive"></a>
    </div>
    <div class="topnav" id="myTopnav">
      <a href="Home.php">Inicio</a>
      <a href="BooksPage.php">Libros</a>
      <a href="likes.php">Me gusta</a>
      <a href="aboutus.php">Sobre nosotros</a>
      <a href="contact.php">Contacto</a>
      <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>
    <div id="whitemode"> 
      <h6>Modo claro</h6>
      <label class="switch">
      <input type="checkbox" id="mode-toggle" Modo claro> <span class="slider round"></span></label>
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
    <span class="big-circle"></span>
      
    <div class="form">
      <div class="contact-info">
        <h3 class="title">Pongámonos en contacto</h3>
        <p class="text">
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe
          dolorum adipisci recusandae praesentium dicta!
        </p>
      <div class="info">
      <div class="information">
        <img src="img/location.png" class="icon" alt="" />
          <p>dirección</p>
      </div>
      <div class="information">
        <img src="img/email.png" class="icon" alt="" />
          <p>empresa@gmail.com</p>
      </div>
      <div class="information">
        <img src="img/phone.png" class="icon" alt="" />
          <p>teléfono</p>
      </div>
  </div>

  <div class="social-media">
    <p>Conéctate con nosotros :</p>
    <div class="social-icons">
      <a href="#">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="#">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="#">
        <i class="fab fa-instagram"></i>
        </a>
      <a href="#">
        <i class="fab fa-linkedin-in"></i>
      </a>
    </div>
  </div>
  </div>

  <div class="contact-form">
    <span class="circle one"></span>
    <span class="circle two"></span>
    <form action="index.php" autocomplete="off">
      <h3 class="title">Contáctanos</h3>
      <div class="input-container">
        <input type="text" name="name" class="input" />
        <label for="">Nombre de usuario</label>
          <span>Nombre de usuario</span>
      </div>
      <div class="input-container">
        <input type="email" name="email" class="input" />
        <label for="">Correo electrónico</label>
          <span>Correo electrónico</span>
      </div>
      <div class="input-container">
        <input type="tel" name="phone" class="input" />
        <label for="">Teléfono</label>
          <span>Teléfono</span>
      </div>
      <div class="input-container textarea">
        <textarea name="message" class="input"></textarea>
        <label for="">Mensaje</label>
          <span>Mensaje</span>
      </div>
        <input type="submit" value="Enviar" class="btn" />
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
