<?php
session_start();
require_once 'Controlador/CLibros.php';

// Función para verificar el estado de suscripción
function tieneSubscripcionActiva($userId) {
    $conexion = new Conexion();
    $conn = $conexion->getcon();
    $stmt = $conn->prepare("SELECT EstadoSuscripcion FROM Usuarios WHERE IDUsuario = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['EstadoSuscripcion'] === 'Activa';
}

// Obtener el ID del libro de la URL
$idLibro = $_GET['id'] ?? 0;

// Instanciar el controlador y obtener los datos del libro
$controladorLibros = new CLibros();
$libro = $controladorLibros->obtenerLibroPorId($idLibro);

// Verificar si el libro existe
if (!$libro) {
    header("Location: BooksPage.php");
    exit();
}

// Definir la ruta base
$baseUrl = '/mindsound/Project';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reproductor - <?php echo htmlspecialchars($libro['Titulo']); ?></title>
    <link rel="stylesheet" href="style/Style.css">
    <link rel="stylesheet" href="style/AudioStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
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

    <div class="audioPlayer">
        <h2 class="audioName"><?php echo htmlspecialchars($libro['Titulo']); ?></h2>
        <p class="authorName"><?php echo htmlspecialchars($libro['Autor']); ?></p>
        
        <div class="disk" style="background-image: url('<?php echo $baseUrl . '/' . $libro['RutaPortada']; ?>')"></div>
        
        <div class="progress-container">
            <input type="range" class="bar" min="0" max="100" value="0">
            <div class="time-display">
                <span class="timeNow">00:00</span>
                <span class="audioDuration">00:00</span>
            </div>
        </div>
        
        <div class="controls">
            <button class="btn backwardBtn">
                <i class="fas fa-backward"></i>
            </button>
            <button class="btn playBtn">
                <i class="fas fa-play"></i>
            </button>
            <button class="btn forwardBtn">
                <i class="fas fa-forward"></i>
            </button>
        </div>

        <!-- Elemento de audio oculto -->
        <audio id="audio" src="<?php echo $baseUrl . '/' . $libro['RutaAudio']; ?>"></audio>
    </div>

    <script src="script/script.js"></script>
</body>
</html>
