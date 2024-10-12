<?php
session_start();
// Elimina esta redirección
// if (!isset($_SESSION['usuario_id'])) {
//     header("Location: intranet.php");
//     exit();
// }
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
  <title> LIBROS</title>
</head>
  
<body>

<!-- ____________________________________________ HEADER _______________________________________________________ -->
  
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
      <h6>Modo Claro</h6>
      <label class="switch">
      <input type="checkbox" id="mode-toggle" Modo Claro> <span class="slider round"></span></label>
    </div>
    <div class="search-login-container">
      <div class="search-container">
        <input type="text" name="search" id="searchInput" placeholder="Buscar..." class="search-input">
        <a href="#" class="search-btn">
        <i class="fas fa-search" aria-hidden="true"></i>      
        </a>
      </div>
      <?php if(isset($_SESSION['usuario_id'])): ?>
        <a href="Modelo/PHP/cerrarsesion.php" class="login-btn">Cerrar sesión</a>
      <?php else: ?>
        <a href="intranet.php" class="login-btn">Iniciar sesión</a>
      <?php endif; ?>
    </div>
  </div>
</header>
  
<!-- ____________________________________________ HEADER _______________________________________________________ -->

<!-- ____________________________________________ SLIDER _______________________________________________________ -->
      

<section class="cd-slider">
  <ul>
    <li data-color="#473e3d">
      <div class="content" style="background-image:url(img/slider/01.png)">
        <blockquote >
          <p>AHORA.. ESCUCHARÁS TUS LIBROS FAVORITOS FÁCILMENTE</p>
          
        </blockquote>
      </div>
    </li>
    <li data-color="#634200">
      <div class="content" style="background-image:url(img/slider/02.png)">
        <blockquote>
          <p>PUEDES ESCUCHAR LOS LIBROS QUE QUIERAS.. GRATIS</p>
          
        </blockquote>
      </div>
    </li>
    <li data-color="#022c4d">
      <div class="content" style="background-image:url(img/slider/03.png)">
        <blockquote>
          <p>ADEMÁS.. PUEDES DESCARGAR ESTOS LIBROS</p>
          
        </blockquote>
      </div>
    </li>
  </ul>
  <nav>
    <div><a class="prev" href="#"></a></div>
    <div><a class="next" href="#"></a></div>
  </nav>
</section>

<!-- ____________________________________________ SLIDER _______________________________________________________ -->


<!-- ____________________________________________ CATEGORY _______________________________________________________ -->

  <section class="category">
    <h2 class="section-heading">Categoría</h2>
    <div class="container2">
      <div class="profile"><a href="category/biography.html">
        <img src="img/category/01.png" alt=""><span class="name">BIOGRAFÍA</span></a>
      </div>
      <div class="profile"><a href="category/historical.html">
        <img src="img/category/02.png" alt=""><span class="name">HISTÓRICO </span></a>
      </div>
      <div class="profile"><a href="category/horror.html">
        <img src="img/category/03.png" alt=""><span class="name">TERROR </span></a>
      </div>
      <div class="profile"><a href="category/mystery.html">
        <img src="img/category/04.png" alt=""><span class="name">MISTERIO</span></a>
      </div>
      <div class="profile"><a href="category/novel.html">
        <img src="img/category/06.png" alt=""><span class="name">NOVELA</span></a>
      </div>
      <div class="profile"><a href="category/science.html">
        <img src="img/category/05.png" alt=""><span class="name"> CIENCIA</span></a>
      </div>
      <div class="profile"><a href="category/sports.html">
        <img src="img/category/07.png" alt=""><span class="name">DEPORTES </span></a>
      </div>
    </div>
  </section>

<!-- ____________________________________________ CATEGORY _______________________________________________________ -->


<!-- ____________________________________________ TOP RATED _______________________________________________________ -->

<h1>Mejor valorados</h1>

<div class="toprated">
  <article class="card">
    <img class="card__background" src="img/Books/The Poor Traveler.jpg" alt="Photo of Cartagena's cathedral at the background and some colonial style houses" width="1920" height="2193">
    <div class="card__content | flow">
      <div class="card__content--container | flow">
        <h3 class="card__title">El Pobre Viajero</h3>
        <p class="card__description">
          Reimpresión del original, publicado por primera vez en 1858. La editorial Anatiposi publica libros históricos como reimpresiones.
        </p>
      </div>
      <a href="html Books/The Poor Traveler.html"><button class="card__button" >Leer más</button></a>
    </div>
  </article>
  <article class="card">
    <img class="card__background" src="img/Books/The Three Questions.jpg" alt="Photo of Cartagena's cathedral at the background and some colonial style houses" width="1920" height="2193">
    <div class="card__content | flow">
      <div class="card__content--container | flow">
        <h3 class="card__title">Las Tres Preguntas</h3>
        <p class="card__description">
          es un cuento corto de 1903 del autor ruso León Tolstói como parte de la colección ...
        </p>
      </div>
      <a href="html Books/The Three Questions.html"><button class="card__button" >Leer más</button></a>
    </div>
  </article>
  <article class="card">
    <img class="card__background" src="img/Books/nazret elfosdk.jpg" alt="Photo of Cartagena's cathedral at the background and some colonial style houses" width="1920" height="2193">
    <div class="card__content | flow">
      <div class="card__content--container | flow">
        <h3 class="card__title">Teoría del Pistacho</h3>
        <p class="card__description">
          El libro "Teoría del Pistacho" es una de las creaciones del escritor saudí "Fahd Amer Al-Ahmadi".
        </p>
      </div>
      <a href="html Books/nazret elfosdk.html"><button class="card__button" >Leer más</button></a>
    </div>
  </article>
  <article class="card">
    <img class="card__background" src="img/Books/gadid nafsk.jpg" alt="Photo of Cartagena's cathedral at the background and some colonial style houses" width="1920" height="2193">
    <div class="card__content | flow">
      <div class="card__content--container | flow">
        <h3 class="card__title">Renuévate</h3>
        <p class="card__description">
          Renuévate: Cómo convertirte en la persona que siempre has querido ser por Steve Chandler
        </p>
      </div>
      <a href="html Books/gadid nafsk.html"><button class="card__button" >Leer más</button></a>
    </div>
  </article> 
</div>

<!-- ____________________________________________ TOP RATED _______________________________________________________ -->

<!-- ____________________________________________ BOOKS SECTION _______________________________________________________ -->

<h1>Libros</h1>

<div id="container"></div>  
  <a class="seemorelink" href="BooksPage.html"><button class="seemore" ><span >Ver más </span></button></a>


<!-- ____________________________________________ BOOKS SECTION _______________________________________________________ -->

<!-- ____________________________________________ OUR PARTNERS _______________________________________________________ -->

<h1>NUESTROS Socios</h1>
<div class="marquee">
  
  <ul class="marquee-content">
    
    <li><i><img src="img/partner/01.png"  ></i></li>
    <li><i><img src="img/partner/02.png"  ></i></li>
    <li><i><img src="img/partner/03.jpg"></i></li>
    <li><i><img src="img/partner/04.jpg"  ></i></li>
    <li><i><img src="img/partner/05.png"  ></i></li>
    <li><i><img src="img/partner/06.png"  ></i></li>
    <li><i><img src="img/partner/07.png"  ></i></li>

  </ul>
</div>

<!-- ____________________________________________ OUR PARTNERS _______________________________________________________ -->

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
