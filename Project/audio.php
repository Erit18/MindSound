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
  <title>Audio</title>
</head>
  
<body>

<!-- ____________________________________________ ENCABEZADO _______________________________________________________ -->
  
<header id="header">
  <div id="nav">
    <!-- <div id="logo">
      <a href="Home.php"><img src="img/logo/logo.png" alt="imagen de prueba" class="responsive"></a>
    </div> -->
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

<!-- ____________________________________________ SECCIÓN DE AUDIO _______________________________________________________ -->
  
    <audio src="" id="audio"></audio>
    <div class="audioPlayer">
        <h1 class="audioName" id="AudioName">Audio Uno</h1>
        <p class="authorName" id="AuthorName">Zezo</p>
        <div class="disk" style="background-image: url(img/Books/The\ Poor\ Traveler.jpg);">   </div>
        <div class="audioSlider">
            <input type="range" value="0" class="bar">
            <span class="timeNow">00:00</span>
            <span class="audioDuration">00:00</span>
        </div>

        <div  class="controls"  >
          <button class="btn backwardBtn"><img src="img/pre.png" alt=""> </button>
          <button class="playBtn pause">
            <span> </span> 
            <span> </span>
          </button>
          <button class="btn forwardBtn"><img src="img/nxt.png" alt=""> </button>
        </div>
    </div>

    
<!-- ____________________________________________ SECCIÓN DE AUDIO _______________________________________________________ -->


    <script src="https://hammerjs.github.io/dist/hammer.js"></script>
    <script src="script/data.js"></script>
    <script src="script/script.js"></script>
    <script>
      const modeToggle = document.getElementById('mode-toggle');
      const body = document.body;
      modeToggle.addEventListener('click', () => {
      body.classList.toggle('white-mode');
      });
    </script>

</body>
</html>


