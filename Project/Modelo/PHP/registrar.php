<?php
// Incluye la clase de conexión
include '../../Controlador/BD/Conexion.php';

// Obtén la conexión
$conexion = new Conexion();
$con = $conexion->getcon();

// Captura los datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$contrasena = $_POST['password'];
$contrasena2 = $_POST['password2'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$genero = $_POST['genero'];

// Verifica que todos los campos estén llenos
if (empty($nombre) || empty($apellido) || empty($correo) || empty($contrasena) || empty($contrasena2) || empty($fechaNacimiento) || empty($genero)) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Por favor, rellene todos los campos!',
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
}

// Verifica el formato del correo electrónico
if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || preg_match('/@.*\./', $correo) === 0) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Por favor, ingrese un correo electrónico válido!',
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
}

// Verifica que la fecha de nacimiento no sea del año actual o futuro
$fechaActual = new DateTime();
$fechaNacimientoDate = new DateTime($fechaNacimiento);

// Nueva validación para edad máxima (120 años)
$edadMaxima = 100;
$fechaMaximaNacimiento = (new DateTime())->modify("-$edadMaxima years");
if ($fechaNacimientoDate < $fechaMaximaNacimiento) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'La fecha de nacimiento no es válida. La edad máxima permitida es de $edadMaxima años.',
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
}

if ($fechaNacimientoDate->format('Y') >= $fechaActual->format('Y')) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'La fecha de nacimiento no puede ser del año actual o futuro!',
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
}

// Verifica que el usuario tenga al menos 18 años
$edadMinima = 18;
$fechaMinimaNacimiento = (new DateTime())->modify("-$edadMinima years");
if ($fechaNacimientoDate > $fechaMinimaNacimiento) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Debes tener al menos $edadMinima años para registrarte!',
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
}

// Verifica que las contraseñas coincidan
if ($contrasena !== $contrasena2) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Las contraseñas no coinciden, por favor verifique!',
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
}

// Verifica que el nombre y apellido no contengan caracteres no permitidos
if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]*$/", $nombre) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]*$/", $apellido)) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El nombre y apellido solo pueden contener letras y espacios!',
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
}

// Verifica si el correo ya está en uso
$stmt = $con->prepare("SELECT * FROM Usuarios WHERE CorreoElectronico = :correo");
$stmt->bindParam(':correo', $correo);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Este Correo ya está en uso, pruebe con uno diferente!',
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
}

// Hashea la contraseña
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

// Usa el procedimiento almacenado SP_INSERTAR_USUARIO
$query = "CALL SP_INSERTAR_USUARIO(:nombre, :apellido, :correo, :contrasena, :fechaNacimiento, :genero)";
$stmt = $con->prepare($query);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':apellido', $apellido);
$stmt->bindParam(':correo', $correo);
$stmt->bindParam(':contrasena', $contrasenaHash);
$stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
$stmt->bindParam(':genero', $genero);

if ($stmt->execute()) {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Registro Exitoso!',
            text: 'Bienvenido a MindSound. Tu cuenta ha sido creada con éxito.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = '../../intranet.php';
            }
        });
        </script>
        </body>";
} else {
    echo "<body>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Ocurrió un error en el registro!',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = '../../intranet.php';
            }
        });
        </script>
        </body>";
}
