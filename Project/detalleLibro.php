<?php
require_once 'Controlador/CLibros.php';

$idLibro = $_GET['id'] ?? 0;
$controladorLibros = new CLibros();
$libro = $controladorLibros->obtenerLibroPorId($idLibro);

if (!$libro) {
    // Manejar el caso de libro no encontrado
    header("Location: BooksPage.php");
    exit();
}

// Definir la URL base del proyecto
$baseUrl = '/Project'; // Ajusta esto según la estructura de tu proyecto
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- ... (tus metadatos y enlaces CSS) ... -->
    <title><?php echo htmlspecialchars($libro['Titulo']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- ... (tu header) ... -->

    <div id="contin">
        <div class="wrapper">
            <div class="main_card">
                <div class="card_left">
                    <div class="card_datails">
                        <h1><?php echo htmlspecialchars($libro['Titulo']); ?></h1>
                        <div class="card_cat">
                            <p class="PG">Autor: <?php echo htmlspecialchars($libro['Autor']); ?></p>
                            <p class="year">Narrador: <?php echo htmlspecialchars($libro['Narrador']); ?></p>
                            <p class="genre">Duración: <?php echo $libro['Duracion']; ?></p>
                            <p class="time">Precio: $<?php echo $libro['Precio']; ?></p>
                        </div>
                        <p class="disc"><?php echo nl2br(htmlspecialchars($libro['Descripcion'])); ?></p>
                        <?php if ($libro['EsGratuito']): ?>
                            <div class="audio-player">
                                <audio controls>
                                    <source src="<?php echo $baseUrl . '/' . $libro['RutaAudio']; ?>" type="audio/mpeg">
                                    Tu navegador no soporta el elemento de audio.
                                </audio>
                            </div>
                        <?php else: ?>
                            <a href="#" class="social-btn">Agregar al carrito</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card_right">
                    <div class="img_container">
                        <img src="<?php echo $baseUrl . '/' . $libro['RutaPortada']; ?>" alt="<?php echo htmlspecialchars($libro['Titulo']); ?>">
                    </div>
                    <?php if ($libro['EsGratuito']): ?>
                        <div class="play_btn">
                            <a href="#" onclick="document.querySelector('audio').play(); return false;">
                                <i class="fas fa-play-circle"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ... (tu footer) ... -->
</body>
</html>
