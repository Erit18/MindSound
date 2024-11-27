<?php
session_start();
// Agrega esto temporalmente para debug
echo "<!-- Debug: ";
var_dump($_SESSION);
echo " -->";
require_once 'Controlador/CLibros.php';
require_once 'Controlador/CLikes.php';

$idLibro = $_GET['id'] ?? 0;
$controladorLibros = new CLibros();
$controladorLikes = new CLikes();
$libro = $controladorLibros->obtenerLibroPorId($idLibro);
$generos = $controladorLibros->obtenerGenerosLibro($idLibro);

// Verificar si el libro está guardado para el usuario actual
$esLibroGuardado = false;
if (isset($_SESSION['usuario'])) {
    $esLibroGuardado = $controladorLikes->verificarLibroGuardado(
        $_SESSION['usuario']['IDUsuario'], 
        $idLibro
    );
}

if (!$libro) {
    header("Location: BooksPage.php");
    exit();
}

$baseUrl = '/mindsound/Project';

// Función para verificar el estado de suscripción (asegúrate de que esta función esté definida o importada)
function tieneSubscripcionActiva($userId) {
    // Implementación de la función
}

// Verifica la sesión y asigna los valores correctamente
$isLoggedIn = isset($_SESSION['usuario']) ? 'true' : 'false';
$userId = isset($_SESSION['usuario']) ? $_SESSION['usuario']['IDUsuario'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($libro['Titulo']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style/Style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #22312A;
            color: #fff;
            margin: 0;
            padding: 0;
            padding-left: 250px; /* Espacio para la barra lateral */
        }
        .main_card {
            background: linear-gradient(to right, #d85d65, #730F16);
            border-radius: 30px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            display: flex;
            margin: 100px auto;
            max-width: 760px;
            overflow: hidden;
        }
        .card_left {
            padding: 30px;
            width: 60%;
        }
        .card_right {
            width: 40%;
        }
        .card_right img {
            height: 100%;
            object-fit: cover;
            width: 100%;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .card_cat {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .card_cat p {
            background: #d85d65;
            border-radius: 15px;
            margin-right: 10px;
            margin-bottom: 10px;
            padding: 5px 10px;
        }
        .disc {
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .action-button {
            background-color: #d85d65;
            border: none;
            border-radius: 12px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            padding: 10px 20px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-right: 10px;
            flex: 1;
            text-align: center;
        }
        .action-button:last-child {
            margin-right: 0;
        }
        .action-button:hover {
            background-color: #c74c54;
        }
        @media (max-width: 768px) {
            body {
                padding-left: 0;
            }
            .main_card {
                flex-direction: column;
                margin: 20px;
            }
            .card_left, .card_right {
                width: 100%;
            }
            .card_right img {
                height: 300px;
            }
            .action-buttons {
                flex-direction: column;
            }
            .action-button {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body data-user-logged-in="<?php echo $isLoggedIn; ?>" 
      data-user-id="<?php echo $userId; ?>">

<!-- Barra lateral -->
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

<!-- Contenido principal -->
<div class="main_card">
    <div class="card_left">
        <h1><?php echo htmlspecialchars($libro['Titulo']); ?></h1>
        
        <!-- Información básica en una sección -->
        <div class="book-info">
            <div class="info-item">
                <i class="fas fa-user"></i>
                <span>Autor:</span> <?php echo htmlspecialchars($libro['Autor']); ?>
            </div>
            <div class="info-item">
                <i class="fas fa-microphone"></i>
                <span>Narrador:</span> <?php echo htmlspecialchars($libro['Narrador']); ?>
            </div>
            <div class="info-item">
                <i class="fas fa-clock"></i>
                <span>Duración:</span> <?php echo $libro['Duracion']; ?>
            </div>
        </div>

        <!-- Sección de géneros -->
        <div class="generos-section">
            <h3><i class="fas fa-tags"></i> Géneros</h3>
            <div class="generos-tags">
                <?php foreach ($generos as $genero): ?>
                    <span class="genero-tag">
                        <?php echo htmlspecialchars($genero['NombreGenero']); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

        <p class="disc"><?php echo nl2br(htmlspecialchars($libro['Descripcion'])); ?></p>
        
        <!-- Botones de acción -->
        <div class="action-buttons">
            <a href="audio.php?id=<?php echo $libro['IDLibro']; ?>" class="action-button">
                <i class="fas fa-play"></i> Reproducir
            </a>
            <a href="<?php echo $baseUrl . '/' . $libro['RutaAudio']; ?>" download class="action-button">
                <i class="fas fa-download"></i> Descargar
            </a>
            <button class="action-button like-button" data-book-id="<?php echo $libro['IDLibro']; ?>">
                <i class="fas fa-heart <?php echo (isset($esLibroGuardado) && $esLibroGuardado) ? 'liked' : ''; ?>"></i> Me gusta
            </button>
        </div>
    </div>
    <div class="card_right">
        <img src="<?php echo $baseUrl . '/' . $libro['RutaPortada']; ?>" alt="<?php echo htmlspecialchars($libro['Titulo']); ?>">
    </div>
</div>

<script src="script/HomeScript.js"></script>
<script src="script/like.js"></script>
</body>
</html>