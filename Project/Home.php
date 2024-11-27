<?php
session_start();
// Elimina esta redirección
// if (!isset($_SESSION['usuario_id'])) {
//     header("Location: intranet.php");
//     exit();
// }

require_once 'Controlador/CLibros.php';

$controladorLibros = new CLibros();
$libros = $controladorLibros->obtenerLibros();

$baseUrl = '/mindsound/Project'; // Ajusta esto según la estructura de tu proyecto

// Función para verificar el estado de suscripción
function tieneSubscripcionActiva($userId) {
    $conexion = new Conexion();
    $conn = $conexion->getcon();
    $stmt = $conn->prepare("SELECT EstadoSuscripcion FROM Usuarios WHERE IDUsuario = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['EstadoSuscripcion'] === 'Activa';
}
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
  <style>
        .mensaje-exito {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            margin: 20px auto;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            max-width: 80%;
            transition: opacity 0.5s ease-in-out;
        }
        .oculto {
            opacity: 0;
        }
    </style>
</head>
  
<body>

<?php include 'Vista/header.php'; ?>

<?php
if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_SESSION['mensaje_exito'])) {
    echo '<div id="mensajeExito" class="mensaje-exito">' . htmlspecialchars($_SESSION['mensaje_exito']) . '</div>';
    unset($_SESSION['mensaje_exito']); // Elimina el mensaje después de mostrarlo
}
?>

<!--  HEADER-->
<header id="header">
  <div id="nav">
    
    <div class="topnav" id="myTopnav">
      <a href="Home.php">Inicio</a>
      <a href="BooksPage.php">Libros</a>
      <a href="likes.php">Me gusta</a>
      <a href="aboutus.php">Sobre Nosotros</a>
      <a href="contact.php">Contacto</a>
      <?php if(isset($_SESSION['usuario_id'])): ?>
        <?php if($_SESSION['usuario_rol'] !== 'Administrador'): ?>
          <?php if(tieneSubscripcionActiva($_SESSION['usuario_id'])): ?>
            <a href="gestionar_suscripcion.php">Gestionar Suscripción</a>
          <?php else: ?>
            <a href="suscripciones.php">Suscribirse</a>
          <?php endif; ?>
        <?php endif; ?>
      <?php else: ?>
        <a href="intranet.php?redirect=suscripciones.php">Suscribirse</a>
      <?php endif; ?>
      <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>

    <div class="search-container">
      <input type="text" name="search" id="searchInput" placeholder="Buscar..." class="search-input">
      <a href="#" class="search-btn">
      <i class="fas fa-search" aria-hidden="true"></i>      
      </a>
    </div>
    <div class="Container" id="containere">
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
          <p>VIVE LA LECTURA DE UNA NUEVA FORMA</p>
        </blockquote>
      </div>
    </li>
    <li data-color="#634200">
      <div class="content" style="background-image:url(img/slider/02.png)">
        <blockquote>
          <p>MILES DE HISTORIAS, UNA SUSCRIPCIÓN</p>
        </blockquote>
      </div>
    </li>
    <li data-color="#022c4d">
      <div class="content" style="background-image:url(img/slider/03.png)">
        <blockquote>
          <p>LLEVA TU BIBLIOTECA CONTIGO A DONDE VAYAS</p>
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
      <div class="profile"><a href="category/biography.php">
        <img src="img/category/01.png" alt=""><span class="name">BIOGRAFÍA</span></a>
      </div>
      <div class="profile"><a href="category/historical.php">
        <img src="img/category/02.png" alt=""><span class="name">HISTÓRICO</span></a>
      </div>
      <div class="profile"><a href="category/horror.php">
        <img src="img/category/03.png" alt=""><span class="name">TERROR</span></a>
      </div>
      <div class="profile"><a href="category/mystery.php">
        <img src="img/category/04.png" alt=""><span class="name">MISTERIO</span></a>
      </div>
      <div class="profile"><a href="category/novel.php">
        <img src="img/category/06.png" alt=""><span class="name">NOVELA</span></a>
      </div>
      <div class="profile"><a href="category/science.php">
        <img src="img/category/05.png" alt=""><span class="name">CIENCIA</span></a>
      </div>
      <div class="profile"><a href="category/sports.php">
        <img src="img/category/07.png" alt=""><span class="name">DEPORTES</span></a>
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

<div id="container">
    <?php foreach ($libros as $libro): ?>
    <article class="card">
        <img class="card__background" 
             src="<?php echo file_exists($libro['RutaPortada']) ? $baseUrl . '/' . $libro['RutaPortada'] : $baseUrl . '/img/default-cover.jpg'; ?>" 
             alt="<?php echo htmlspecialchars($libro['Titulo']); ?>">
        <div class="card__content">
            <div class="card__content--container">
                <h3 class="card__title"><?php echo htmlspecialchars($libro['Titulo']); ?></h3>
                <p class="card__description">
                    <?php echo htmlspecialchars(substr($libro['Descripcion'], 0, 100)) . '...'; ?>
                </p>
            </div>
            <a href="detalleLibro.php?id=<?php echo $libro['IDLibro']; ?>" class="card__button">Leer más</a>
        </div>
    </article>
    <?php endforeach; ?>
</div>
  <a class="seemorelink" href="BooksPage.php"><button class="seemore" ><span >Ver más </span></button></a>


<!-- ____________________________________________ BOOKS SECTION _______________________________________________________ -->

<!-- ____________________________________________ OUR PARTNERS _______________________________________________________ -->



<!-- ____________________________________________ OUR PARTNERS _______________________________________________________ -->

<!-- ____________________________________________ FOOTER _______________________________________________________ -->
    
<footer>
  <div class="footer-content">
    <h3>Compañía</h3>
    <p>SoundMind es tu destino premium para audiolibros. Ofrecemos una amplia selección de títulos gratuitos, 
    así como una colección exclusiva de audiolibros premium para nuestros suscriptores. Nuestra misión es hacer 
    que la literatura sea accesible para todos, en cualquier momento y lugar. Con SoundMind, puedes sumergirte 
    en historias fascinantes, aprender nuevos conceptos y disfrutar de la narración profesional, todo desde la 
    comodidad de tu dispositivo. Únete a nuestra comunidad de amantes de la lectura auditiva y descubre un 
    nuevo mundo de posibilidades literarias.</p>
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

<?php include 'Vista/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mensajeExito = document.getElementById('mensajeExito');
    if (mensajeExito) {
        setTimeout(function() {
            mensajeExito.style.opacity = '0';
            setTimeout(function() {
                mensajeExito.remove();
            }, 500);
        }, 3000);
    }
});
</script>

</body>
</html>