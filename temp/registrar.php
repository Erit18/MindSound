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
            text: 'Este Correo ya esta en uso, pruebe con uno diferente!',
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

// CAMBIO TEMPORAL: Asignar rol de administrador por defecto
// Para revertir, simplemente comenta la siguiente línea
$rol = 'Administrador';

// Usa una consulta INSERT directa en lugar del procedimiento almacenado
$query = "INSERT INTO Usuarios (Nombre, Apellido, CorreoElectronico, Contraseña, FechaNacimiento, Genero, Rol) 
          VALUES (:nombre, :apellido, :correo, :contrasena, :fechaNacimiento, :genero, :rol)";
$stmt = $con->prepare($query);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':apellido', $apellido);
$stmt->bindParam(':correo', $correo);
$stmt->bindParam(':contrasena', $contrasenaHash);
$stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
$stmt->bindParam(':genero', $genero);
$stmt->bindParam(':rol', $rol);

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
