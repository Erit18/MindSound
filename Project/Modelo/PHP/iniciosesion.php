<?php

session_start();

include '../../Controlador/BD/Conexion.php';

$correo = $_POST['correo'];
$contrasena = $_POST['password'];

if (empty($correo) || empty($contrasena)) {
    // Mostrar error: "Por favor, ingrese tanto el correo como la contraseña"
    exit();
}

$conexion = new Conexion();
$con = $conexion->getcon();

$stmt = $con->prepare("CALL SP_VERIFICAR_CREDENCIALES(:correo)");
$stmt->bindParam(':correo', $correo);
$stmt->execute();

if($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if(password_verify($contrasena, $usuario['Contraseña'])) {
        $_SESSION['usuario_id'] = $usuario['IDUsuario'];
        $_SESSION['usuario_nombre'] = $usuario['Nombre'];
        $_SESSION['usuario_apellido'] = $usuario['Apellido'];
        $_SESSION['usuario_correo'] = $usuario['CorreoElectronico'];
        $_SESSION['usuario_rol'] = $usuario['Rol'];
        
        // Redirigir según el rol del usuario
        if ($usuario['Rol'] == 'Administrador') {
            header("Location: ../../admin.php");
        } else {
            header("Location: ../../home.php");
        }
        exit();
    }
}

echo "<body>";
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Correo electrónico o contraseña incorrectos',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Aceptar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = '../../intranet.php';
        }
    });
    </script>
    </body>";
exit();

?>
