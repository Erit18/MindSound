<?php
require_once 'BD/Conexion.php';

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'eliminar':
            eliminarUsuario();
            break;
        case 'editar':
            editarUsuario();
            break;
        case 'agregar':
            agregarUsuario();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
            break;
    }
}

function eliminarUsuario() {
    $idUsuario = $_POST['idUsuario'] ?? '';
    
    if (!$idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ELIMINAR_USUARIO(?)");
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el usuario']);
        }
    } catch (PDOException $e) {
        error_log("Error al eliminar usuario: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function editarUsuario() {
    $idUsuario = $_POST['idUsuario'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if (!$idUsuario || !$nombre || !$apellido || !$email || !$fechaNacimiento || !$genero || !$rol) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
        return;
    }

    $rolesPermitidos = ['Usuario', 'Administrador'];
    if (!in_array($rol, $rolesPermitidos)) {
        echo json_encode(['status' => 'error', 'message' => 'Rol no válido']);
        return;
    }

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_ACTUALIZAR_USUARIO(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(3, $apellido, PDO::PARAM_STR);
        $stmt->bindParam(4, $email, PDO::PARAM_STR);
        $stmt->bindParam(5, $fechaNacimiento, PDO::PARAM_STR);
        $stmt->bindParam(6, $genero, PDO::PARAM_STR);
        $stmt->bindParam(7, $rol, PDO::PARAM_STR);

        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el usuario']);
        }
    } catch (PDOException $e) {
        error_log("Error al actualizar usuario: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function agregarUsuario() {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? 'Usuario';

    if (!$nombre || !$apellido || !$email || !$fechaNacimiento || !$genero || !$password || !$rol) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
        return;
    }

    $rolesPermitidos = ['Usuario', 'Administrador'];
    if (!in_array($rol, $rolesPermitidos)) {
        echo json_encode(['status' => 'error', 'message' => 'Rol no válido']);
        return;
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_AGREGAR_USUARIO(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(2, $apellido, PDO::PARAM_STR);
        $stmt->bindParam(3, $email, PDO::PARAM_STR);
        $stmt->bindParam(4, $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(5, $fechaNacimiento, PDO::PARAM_STR);
        $stmt->bindParam(6, $genero, PDO::PARAM_STR);
        $stmt->bindParam(7, $rol, PDO::PARAM_STR);

        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Usuario agregado con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo agregar el usuario']);
        }
    } catch (PDOException $e) {
        error_log("Error al agregar usuario: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

function verificarCredenciales($email, $password) {
    try {
        $conexion = new Conexion();
        $conn = $conexion->getcon();

        $stmt = $conn->prepare("CALL SP_VERIFICAR_CREDENCIALES(?)");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['Contraseña'])) {
            // Las credenciales son correctas
            return $usuario;
        } else {
            // Las credenciales son incorrectas
            return false;
        }
    } catch (PDOException $e) {
        error_log("Error al verificar credenciales: " . $e->getMessage());
        return false;
    }
}
