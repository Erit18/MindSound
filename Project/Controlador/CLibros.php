<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

function captureErrors($errno, $errstr, $errfile, $errline) {
    global $errors;
    $errors[] = "Error [$errno] $errstr en $errfile:$errline";
}

set_error_handler("captureErrors");

$errors = [];

require_once __DIR__ . '/BD/Conexion.php';

class CLibros {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function obtenerLibros() {
        try {
            $conn = $this->conexion->getcon();
            $stmt = $conn->prepare("CALL SP_OBTENER_LIBROS()");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener libros: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerLibroPorId($idLibro) {
        try {
            $conn = $this->conexion->getcon();
            $stmt = $conn->prepare("CALL SP_OBTENER_LIBRO_POR_ID(?)");
            $stmt->bindParam(1, $idLibro, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener libro por ID: " . $e->getMessage());
            return null;
        }
    }

    public function agregarLibro() {
        // Obtener datos del POST
        $titulo = $_POST['titulo'] ?? '';
        $autor = $_POST['autor'] ?? '';
        $narrador = $_POST['narrador'] ?? '';
        $duracion = $_POST['duracion'] ?? '';
        $fechaPublicacion = $_POST['fechaPublicacion'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $esGratuito = isset($_POST['esGratuito']) ? 1 : 0;

        // Manejar la carga de archivos
        $rutaAudio = $this->subirArchivo('rutaAudio', 'audio/');
        $rutaPortada = $this->subirArchivo('rutaPortada', 'img/Books/');

        error_log("Ruta de audio guardada: " . $rutaAudio);
        error_log("Ruta de portada guardada: " . $rutaPortada);

        // Validar datos
        if (!$titulo || !$autor || !$fechaPublicacion) {
            return json_encode(['status' => 'error', 'message' => 'Título, autor y fecha de publicación son requeridos']);
        }

        // Formatear la fecha
        $fechaFormateada = date('Y-m-d', strtotime($fechaPublicacion));

        try {
            $conn = $this->conexion->getcon();

            $stmt = $conn->prepare("CALL SP_AGREGAR_LIBRO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(2, $autor, PDO::PARAM_STR);
            $stmt->bindParam(3, $narrador, PDO::PARAM_STR);
            $stmt->bindParam(4, $duracion, PDO::PARAM_STR);
            $stmt->bindParam(5, $fechaFormateada, PDO::PARAM_STR);
            $stmt->bindParam(6, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(7, $rutaAudio, PDO::PARAM_STR);
            $stmt->bindParam(8, $rutaPortada, PDO::PARAM_STR);
            $stmt->bindParam(9, $precio, PDO::PARAM_STR);
            $stmt->bindParam(10, $esGratuito, PDO::PARAM_BOOL);

            $stmt->execute();

            return json_encode(['status' => 'success', 'message' => 'Libro agregado con éxito']);
        } catch (PDOException $e) {
            return json_encode(['status' => 'error', 'message' => 'Error al agregar libro: ' . $e->getMessage()]);
        }
    }

    public function actualizarLibro() {
        $idLibro = $_POST['idLibro'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $autor = $_POST['autor'] ?? '';
        $narrador = $_POST['narrador'] ?? '';
        $duracion = $_POST['duracion'] ?? '';
        $fechaPublicacion = $_POST['fechaPublicacion'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $esGratuito = isset($_POST['esGratuito']) ? 1 : 0;

        // Manejar la carga de archivos
        $rutaAudio = $this->subirArchivo('rutaAudio', 'imagenes') ?: $_POST['rutaAudioActual'] ?? '';
        $rutaPortada = $this->subirArchivo('rutaPortada', 'imagenes') ?: $_POST['rutaPortadaActual'] ?? '';

        if (!$idLibro || !$titulo || !$autor || !$fechaPublicacion) {
            return json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }

        try {
            $conn = $this->conexion->getcon();
            $stmt = $conn->prepare("CALL SP_ACTUALIZAR_LIBRO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $idLibro, PDO::PARAM_INT);
            $stmt->bindParam(2, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(3, $autor, PDO::PARAM_STR);
            $stmt->bindParam(4, $narrador, PDO::PARAM_STR);
            $stmt->bindParam(5, $duracion, PDO::PARAM_STR);
            $stmt->bindParam(6, $fechaPublicacion, PDO::PARAM_STR);
            $stmt->bindParam(7, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(8, $rutaAudio, PDO::PARAM_STR);
            $stmt->bindParam(9, $rutaPortada, PDO::PARAM_STR);
            $stmt->bindParam(10, $precio, PDO::PARAM_STR);
            $stmt->bindParam(11, $esGratuito, PDO::PARAM_BOOL);

            $stmt->execute();

            return json_encode(['status' => 'success', 'message' => 'Libro actualizado con éxito']);
        } catch (PDOException $e) {
            return json_encode(['status' => 'error', 'message' => 'Error al actualizar libro: ' . $e->getMessage()]);
        }
    }

    public function eliminarLibro() {
        $idLibro = $_POST['idLibro'] ?? '';

        if (!$idLibro) {
            return json_encode(['status' => 'error', 'message' => 'ID de libro no proporcionado']);
        }

        try {
            $conn = $this->conexion->getcon();

            $stmt = $conn->prepare("CALL SP_ELIMINAR_LIBRO(?)");
            $stmt->bindParam(1, $idLibro, PDO::PARAM_INT);
            $stmt->execute();

            return json_encode(['status' => 'success', 'message' => 'Libro eliminado con éxito']);
        } catch (PDOException $e) {
            return json_encode(['status' => 'error', 'message' => 'Error al eliminar libro: ' . $e->getMessage()]);
        }
    }

    private function subirArchivo($inputName, $targetDir) {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $tempName = $_FILES[$inputName]['tmp_name'];
            $fileName = basename($_FILES[$inputName]['name']);
            
            // Asegúrate de que el directorio exista
            $fullTargetDir = $_SERVER['DOCUMENT_ROOT'] . '/Project/' . $targetDir;
            if (!file_exists($fullTargetDir)) {
                mkdir($fullTargetDir, 0777, true);
            }
            
            $targetPath = $fullTargetDir . $fileName;
            
            if (move_uploaded_file($tempName, $targetPath)) {
                // Devuelve la ruta relativa al proyecto
                return $targetDir . $fileName;
            }
        }
        return false;
    }
}

// Manejo de solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new CLibros();
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'obtener':
            if (isset($_POST['idLibro'])) {
                $libro = $controlador->obtenerLibroPorId($_POST['idLibro']);
                echo json_encode(['status' => 'success', 'data' => $libro]);
            } else {
                echo json_encode(['status' => 'success', 'data' => $controlador->obtenerLibros()]);
            }
            break;
        case 'agregar':
            echo $controlador->agregarLibro();
            break;
        case 'actualizar':
            echo $controlador->actualizarLibro();
            break;
        case 'eliminar':
            echo $controlador->eliminarLibro();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
            break;
    }
}
