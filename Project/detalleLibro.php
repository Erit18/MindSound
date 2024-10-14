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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- ... (tus metadatos y enlaces CSS) ... -->
    <title><?php echo $libro['Titulo']; ?></title>
</head>
<body>
    <!-- ... (tu header) ... -->

    <div id="contin">
        <div class="wrapper">
            <div class="main_card">
                <div class="card_left">
                    <div class="card_datails">
                        <h1><?php echo $libro['Titulo']; ?></h1>
                        <div class="card_cat">
                            <p class="PG">Autor: <?php echo $libro['Autor']; ?></p>
                            <p class="year">Narrador: <?php echo $libro['Narrador']; ?></p>
                            <p class="genre">Duración: <?php echo $libro['Duracion']; ?></p>
                            <p class="time">Precio: $<?php echo $libro['Precio']; ?></p>
                        </div>
                        <p class="disc"><?php echo $libro['Descripcion']; ?></p>
                        <a href="#" class="social-btn">Agregar al carrito</a>
                    </div>
                </div>
                <div class="card_right">
                    <div class="img_container">
                        <img src="<?php echo $libro['RutaPortada']; ?>" alt="<?php echo $libro['Titulo']; ?>">
                    </div>
                    <div class="play_btn">
                        <a href="<?php echo $libro['RutaAudio']; ?>" target="_blank">
                            <i class="fas fa-play-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ... (tu footer) ... -->
</body>
</html>

