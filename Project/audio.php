<?php
session_start();
require_once 'Controlador/CLibros.php';

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
    <!-- Barra de navegación -->
    <?php include 'nav.php'; ?>

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
