<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['cambio_plan'])) {
    header("Location: Home.php");
    exit();
}

$nuevoPlan = $_SESSION['cambio_plan']['plan'];
$precio = $_SESSION['cambio_plan']['precio'];

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
    <title>Pago para Cambio de Plan</title>
    <link rel="stylesheet" href="style/Style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Lato&amp;display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo/logo.ico">
    <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
    <style>
        .pago-cambio-plan {
            max-width: 600px;
            margin: 20px auto;
            padding: 25px;
            background-color: #22312A;
            border-radius: 15px;
            color: #ffffff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .pago-cambio-plan h1 {
            color: #8F9569;
            text-align: center;
            font-size: 2em;
            margin-top: 5px;
            margin-bottom: 15px;
            background-color: #37310D;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
        }
        .plan-info {
            background-color: #37310D;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 0.9em;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #8F9569;
            font-size: 0.9em;
        }
        .form-group input {
            width: calc(100% - 20px);
            padding: 8px 10px;
            border: 1px solid #8F9569;
            border-radius: 5px;
            background-color: #37310D;
            color: #ffffff;
            font-size: 0.9em;
        }
        .btn-pagar {
            background-color: #730F16;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-pagar:hover {
            background-color: #8B1319;
        }
        /* Estilos adicionales para mejorar la responsividad */
        @media (max-width: 650px) {
            .pago-cambio-plan {
                width: 90%;
                padding: 15px;
            }
            .pago-cambio-plan h1 {
                font-size: 1.8em;
            }
        }
    </style>
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

    <main>
        <div class="pago-cambio-plan">
            <h1>Pago para Cambio de Plan</h1>
            <div class="plan-info">
                <p>Estás cambiando al plan <?php echo ucfirst($nuevoPlan); ?></p>
                <p>Precio: $<?php echo $precio; ?>/mes</p>
            </div>

            <form action="confirmar_cambio_plan.php" method="post">
                <input type="hidden" name="nuevo_plan" value="<?php echo $nuevoPlan; ?>">
                <input type="hidden" name="precio" value="<?php echo $precio; ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre en la tarjeta:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="numero_tarjeta">Número de tarjeta:</label>
                    <input type="text" id="numero_tarjeta" name="numero_tarjeta" required>
                </div>
                
                <div class="form-group">
                    <label for="fecha_expiracion">Fecha de expiración:</label>
                    <input type="text" id="fecha_expiracion" name="fecha_expiracion" placeholder="MM/AA" required>
                </div>
                
                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" required>
                </div>
                
                <button type="submit" class="btn-pagar">Pagar y Cambiar Plan</button>
            </form>
        </div>
    </main>

    <!-- FOOTER -->
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

    <script src="https://hammerjs.github.io/dist/hammer.js"></script>
    <script src="script/HomeScript.js"></script>
    <script>
        const modeToggle = document.getElementById('mode-toggle');
        const body = document.body;
        modeToggle.addEventListener('click', () => {
            body.classList.toggle('white-mode');
        });
    </script>
</body>
</html>
