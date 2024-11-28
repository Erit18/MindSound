<?php
session_start();
require_once 'Controlador/BD/Conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: intranet.php");
    exit();
}

$conexion = new Conexion();
$conn = $conexion->getcon();

// Obtener información de la suscripción actual
$stmt = $conn->prepare("
    SELECT s.*, p.FechaPago 
    FROM Suscripciones s 
    LEFT JOIN Pagos p ON s.IDSuscripcion = p.IDSuscripcion 
    WHERE s.IDUsuario = ? 
    AND s.EstadoSuscripcion = 'Activa' 
    ORDER BY s.FechaInicio DESC 
    LIMIT 1
");
$stmt->execute([$_SESSION['usuario_id']]);
$suscripcion = $stmt->fetch(PDO::FETCH_ASSOC);

// Calcular días restantes
$diasRestantes = 0;
if ($suscripcion) {
    $fechaFin = new DateTime($suscripcion['FechaFin']);
    $hoy = new DateTime();
    $diasRestantes = $fechaFin->diff($hoy)->days;
    
    // Si la fecha fin es anterior a hoy, mostrar 0 días
    if ($hoy > $fechaFin) {
        $diasRestantes = 0;
    }
}

// Definir los precios de los planes
$precios = [
    'basica' => 9.99,
    'normal' => 14.99,
    'premium' => 19.99
];

$baseUrl = '/Project'; // Ajusta esto según la estructura de tu proyecto

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
    <title>Gestionar Suscripción</title>
    <link rel="stylesheet" href="style/Style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Lato&amp;display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo/logo.ico">
    <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
    <style>
        .gestion-suscripcion {
            max-width: 800px;
            margin: 10px auto; /* Reducido aún más */
            padding: 20px 25px; /* Ajustado el padding */
            background-color: #22312A;
            border-radius: 15px;
            color: #ffffff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .gestion-suscripcion h1 {
            color: #8F9569;
            text-align: center;
            font-size: 2.2em;
            margin-top: 5px; /* Nuevo: mueve el título hacia arriba */
            margin-bottom: 15px; /* Reducido para acercar más a la tarjeta */
            background-color: #37310D; /* Fondo para el título */
            padding: 10px;
            border-radius: 10px 10px 0 0; /* Bordes redondeados solo arriba */
            box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
        }
        .suscripcion-actual, .cambiar-plan {
            background-color: #37310D;
            padding: 20px; /* Reducido de 25px */
            margin-bottom: 25px; /* Reducido de 30px */
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .suscripcion-actual h2, .cambiar-plan h2 {
            color: #8F9569;
            font-size: 1.6em; /* Reducido de 1.8em */
            margin-bottom: 15px; /* Reducido de 20px */
        }
        .suscripcion-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px; /* Reducido de 15px */
        }
        .suscripcion-info p {
            margin: 8px 0; /* Reducido de 10px */
            font-size: 1em; /* Reducido de 1.1em */
        }
        .suscripcion-info strong {
            color: #8F9569;
        }
        .plan-options {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px; /* Reducido de 20px */
        }
        .plan-option {
            background-color: #22312A;
            padding: 12px; /* Reducido de 15px */
            border-radius: 8px;
            text-align: center;
            flex-basis: 30%;
            transition: all 0.3s ease;
        }
        .plan-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .plan-option h3 {
            color: #8F9569;
            margin-bottom: 8px; /* Reducido de 10px */
        }
        .plan-option p {
            font-size: 1.1em; /* Reducido de 1.2em */
            font-weight: bold;
        }
        .btn-cambiar-plan {
            background-color: #730F16;
            color: white;
            padding: 10px 20px; /* Reducido de 12px 25px */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em; /* Reducido de 1.1em */
            transition: background-color 0.3s ease;
            display: block;
            width: 100%;
            margin-top: 15px; /* Reducido de 20px */
        }
        .btn-cambiar-plan:hover {
            background-color: #8B1319;
        }
        select {
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            background-color: #37310D;
            color: #ffffff;
            border: 1px solid #8F9569;
            font-size: 1em;
        }
    </style>
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

    <main class="gestion-suscripcion">
        <h1>Gestionar Suscripción</h1>
        
        <?php if(isset($_SESSION['mensaje_exito'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['mensaje_exito'];
                    unset($_SESSION['mensaje_exito']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if ($suscripcion): ?>
            <div class="suscripcion-actual">
                <h2>Tu Suscripción Actual</h2>
                <div class="suscripcion-info">
                    <p><strong>Plan:</strong> <?php echo htmlspecialchars(ucfirst($suscripcion['TipoSuscripcion'])); ?></p>
                    <p><strong>Fecha de inicio:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($suscripcion['FechaInicio']))); ?></p>
                    <p><strong>Fecha de fin:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($suscripcion['FechaFin']))); ?></p>
                    <p><strong>Días restantes:</strong> <?php echo $diasRestantes; ?></p>
                </div>
                
                <div class="cancelar-suscripcion">
                    <button type="button" class="btn-cancelar" onclick="confirmarCancelacion()">
                        Cancelar Suscripción
                    </button>
                    <p class="cancelar-info">Al cancelar, mantendrás el acceso hasta el final del período actual.</p>
                </div>
            </div>

            <div class="cambiar-plan">
                <h2>Cambiar tu Plan</h2>
                <div class="plan-options">
                    <div class="plan-option">
                        <h3>Básica</h3>
                        <p>$<?php echo $precios['basica']; ?>/mes</p>
                    </div>
                    <div class="plan-option">
                        <h3>Normal</h3>
                        <p>$<?php echo $precios['normal']; ?>/mes</p>
                    </div>
                    <div class="plan-option">
                        <h3>Premium</h3>
                        <p>$<?php echo $precios['premium']; ?>/mes</p>
                    </div>
                </div>
                <form action="proceso_cambio_plan.php" method="post">
                    <select name="nuevo_plan" required>
                        <option value="">Selecciona un nuevo plan</option>
                        <option value="basica" <?php echo strtolower($suscripcion['TipoSuscripcion']) == 'básica' ? 'disabled' : ''; ?>>Plan Básico - $<?php echo $precios['basica']; ?>/mes</option>
                        <option value="normal" <?php echo strtolower($suscripcion['TipoSuscripcion']) == 'normal' ? 'disabled' : ''; ?>>Plan Normal - $<?php echo $precios['normal']; ?>/mes</option>
                        <option value="premium" <?php echo strtolower($suscripcion['TipoSuscripcion']) == 'premium' ? 'disabled' : ''; ?>>Plan Premium - $<?php echo $precios['premium']; ?>/mes</option>
                    </select>
                    <button type="submit" class="btn-cambiar-plan">Cambiar mi Plan</button>
                </form>
            </div>
        <?php else: ?>
            <p>No tienes una suscripción activa. <a href="suscripciones.php" style="color: #8F9569;">Suscríbete ahora</a></p>
        <?php endif; ?>
    </main>

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
    <div id="modalCancelar" class="modal">
        <div class="modal-content">
            <h3>¿Estás seguro de que deseas cancelar tu suscripción?</h3>
            <p>Si cancelas tu suscripción:</p>
            <ul>
                <li>Mantendrás el acceso hasta <?php echo htmlspecialchars(date('d/m/Y', strtotime($suscripcion['FechaFin']))); ?></li>
                <li>No se te cobrará el siguiente período</li>
                <li>Perderás acceso a contenido premium después de la fecha de finalización</li>
            </ul>
            <div class="modal-buttons">
                <form action="cancelar_suscripcion.php" method="POST">
                    <button type="submit" class="btn-confirmar-cancelar">Sí, cancelar suscripción</button>
                </form>
                <button type="button" class="btn-cancelar-modal" onclick="cerrarModal()">No, mantener suscripción</button>
            </div>
        </div>
    </div>
    <script>
    function confirmarCancelacion() {
        const modal = document.getElementById('modalCancelar');
        if (modal) {
            modal.style.display = 'block';
        } else {
            console.error('Modal no encontrado');
        }
    }

    function cerrarModal() {
        const modal = document.getElementById('modalCancelar');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Cerrar modal si se hace clic fuera de él
    window.onclick = function(event) {
        const modal = document.getElementById('modalCancelar');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
    </script>
    <!-- Modal de confirmación -->
    <div id="modalConfirmarCambio" class="modal">
        <div class="modal-content">
            <h3>Confirmar Cambio de Plan</h3>
            <p>Estás a punto de cambiar tu plan a <span id="planSeleccionado"></span>.</p>
            <p>¿Estás seguro de que deseas continuar?</p>
            <div class="modal-buttons">
                <button id="btnConfirmar" class="btn-confirmar-cambiar">Sí, cambiar plan</button>
                <button type="button" class="btn-cancelar-modal" onclick="cerrarModal()">No, mantener suscripción</button>
            </div>
        </div>
    </div>
    <script>
    function confirmarCambioPlan() {
        const nuevoPlan = document.getElementById('nuevo_plan').value;
        const planSeleccionado = document.getElementById('planSeleccionado');
        
        if (nuevoPlan) {
            planSeleccionado.textContent = nuevoPlan.charAt(0).toUpperCase() + nuevoPlan.slice(1);
            document.getElementById('modalConfirmarCambio').style.display = 'block';
        } else {
            alert('Por favor, selecciona un plan.');
        }
    }

    function cerrarModal() {
        document.getElementById('modalConfirmarCambio').style.display = 'none';
    }

    // Cerrar modal si se hace clic fuera de él
    window.onclick = function(event) {
        var modal = document.getElementById('modalConfirmarCambio');
        if (event.target == modal) {
            modal.style.display = 'none';
        }

        var modalCancelar = document.getElementById('modalCancelar');
        if (event.target == modalCancelar) {
            modalCancelar.style.display = 'none';
        }
    }

    // Confirmar el cambio de plan
    document.getElementById('btnConfirmar').onclick = function() {
        document.getElementById('formCambioPlan').submit();
    };
    </script>
</body>
</html>
