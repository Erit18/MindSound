<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=Dancing+Script:wght@700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&amp;display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo/logo.ico">
    <script src="https://kit.fontawesome.com/d2b7381cec.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel="stylesheet" href="style/Style.css">
    <link rel="stylesheet" href="Recursos/style/intranet.css">
    <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
    <title> LIBROS</title>
</head>

<body>
<!-- ____________________________________________ HEADER _______________________________________________________ -->
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

<!--  HEADER-->
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
    <div>
        <?php
        session_start();
        if(isset($_SESSION['usuario_id'])): ?>
            <a href="Modelo/PHP/cerrarsesion.php" class="login-btn">Cerrar sesión</a>
        <?php else: ?>
            <a href="intranet.php" class="login-btn">Iniciar sesión</a>
        <?php endif; ?>
    </div>
    </div>
</header>
<!-- ____________________________________________ HEADER _______________________________________________________ -->



    <main id="main" class="main">
        <div class="contenedor" id="contenedor">
            <div class="form-contenedor crear-cuenta">
                <form action="Modelo/PHP/registrar.php" method="POST">
                    <h1 class="titulo" id="titulo">Registrate</h1>
                    <div class="social-iconos">
                        <a href="#" class="iconos">
                            <i class="fa-brands fa-google-plus-g"></i>
                        </a>
                        <a href="#" class="iconos">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="iconos">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="#" class="iconos">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>
                    <span>O usa tu correo y contraseña</span>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
                    <input type="text" name="apellido" id="apellido" placeholder="Apellido" required>
                    <input type="email" name="correo" id="correo" placeholder="Correo electrónico" required>
                    <input type="password" name="password" id="password" placeholder="Contraseña" required>
                    <input type="password" name="password2" id="password2" placeholder="Repetir Contraseña" required>
                    <div class="form-group">
                        <input type="date" 
                               name="fechaNacimiento" 
                               id="fechaNacimiento" 
                               placeholder="Fecha de nacimiento"
                               required>
                    </div>
                    <select name="genero" id="genero" required>
                        <option value="">Selecciona tu género</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <button type="submit" name="registro">Registrarse</button>
                </form>
            </div>
            <div class="form-contenedor iniciar-sesion">
                <form action="Modelo/PHP/iniciosesion.php" method="POST">
                    <h1 class="titulo" id="titulo">Inicia Sesión</h1>
                    <div class="social-iconos">
                        <a href="#" class="iconos">
                            <i class="fa-brands fa-google-plus-g"></i>
                        </a>
                        <a href="#" class="iconos">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="iconos">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="#" class="iconos">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>
                    <span>Usa tu correo y contraseña</span>
                    <input type="email" name="correo" placeholder="Correo electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <a href="#">¿Olvidaste tu contraseña?</a>
                    <button type="submit">Iniciar Sesión</button>
                </form>
            </div>
            <div class="cambiar-contenedor">
                <div class="cambiar">
                    <div class="cambiar-panel cambiar-izquierda">
                        <h1>Bienvenido!</h1>
                        <p>Unete y disfruta de tus libros favoritos</p>
                        <button class="ocultar" id="login">Iniciar Sesion</button>
                    </div>
                    <div class="cambiar-panel cambiar-derecha">
                        <h1>Buen Día!</h1>
                        <p>Registra todos tus datos y se parte de nosotros</p>
                        <button class="ocultar" id="register">Registrarse</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="Modelo/JavaScript/intranet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ____________________________________________ FOOTER _______________________________________________________ -->
<footer>
    <div class="footer-content">
    <h3>MindSound </h3>
    <p>MindSound es una plataforma de audiolibros diseñada para facilitar el acceso a literatura y contenido educativo para personas ciegas y con discapacidades visuales.
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

</body>

</html>
