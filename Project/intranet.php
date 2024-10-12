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
        <a href="login.php" class="login-btn">Iniciar sesión</a>
        </div>
    </div>
</header>
<!-- ____________________________________________ HEADER _______________________________________________________ -->



    <main id="main" class="main">
        <div class="contenedor" id="contenedor">
            <div class="form-contenedor crear-cuenta">
                <form action="Modelo/PHP/registrar.php" method="POST">
                    <h1>Create una Cuenta</h1>
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
                    <input type="name" name="nombres" id="nombres" placeholder="Nombres y Apellidos">
                    <input type="direccion" name="direccion" id="direccion" placeholder="Direccion">
                    <select id="distritos" name="distritos">
                        <option value="">Distrito</option>
                        <?php
                        
                        $distritos = array("Ancón", "Ate", "Barranco", "Breña", "Carabayllo", "Chaclacayo", "Chorrillos", "Cieneguilla", "Comas", "El Agustino", "Independencia", "Jesús María", "La Molina", "La Victoria", "Lince", "Los Olivos", "Lurigancho", "Lurín", "Magdalena del Mar", "Miraflores", "Pachacámac", "Pucusana", "Pueblo Libre", "Puente Piedra", "Punta Hermosa", "Punta Negra", "Rímac", "San Bartolo", "San Borja", "San Isidro", "San Juan de Lurigancho", "San Juan de Miraflores", "San Luis", "San Martín de Porres", "San Miguel", "Santa Anita", "Santa María del Mar", "Santa Rosa", "Santiago de Surco", "Surquillo", "Villa El Salvador", "Villa María del Triunfo");

                        foreach ($distritos as $distrito) {
                            echo "<option value='$distrito'>$distrito</option>";
                        }
                        ?>
                    </select>
                    <input type="email" name="correo" id="correo" placeholder="Correo">
                    <input type="password" name="password" id="password" placeholder="Contraseña">
                    <input type="password" name="password2" id="password2" placeholder="Repetir Contraseña">
                    <button type="submit" name="registro">Registrarse</button>
                </form>
            </div>
            <div class="form-contenedor iniciar-sesion">
                <form action="Modelo/PHP/iniciosesion.php" method="POST">
                    <h1>Inicia Sesion</h1>
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
                    <input type="email" name="correo" placeholder="Correo">
                    <input type="password" name="password" placeholder="Contraseña">
                    <a href="#">Olvidaste tu contraseña?</a>
                    <button>Iniciar Sesion</button>
                </form>
            </div>
            <div class="cambiar-contenedor">
                <div class="cambiar">
                    <div class="cambiar-panel cambiar-izquierda">
                        <h1>Bienvenido!</h1>
                        <p>Ingresa todos tus datos correspondientes</p>
                        <button class="ocultar" id="login">Iniciar Sesion</button>
                    </div>
                    <div class="cambiar-panel cambiar-derecha">
                        <h1>Buen Dia!</h1>
                        <p>Registra todos tus datos!</p>
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

</body>

</html>