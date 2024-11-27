<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function captureErrors($errno, $errstr, $errfile, $errline) {
    global $errors;
    $errors[] = "Error [$errno] $errstr en $errfile:$errline";
}

set_error_handler("captureErrors");

$errors = [];

require_once __DIR__ . '/BD/Conexion.php';

class CLibros {
    public $conexion;

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
        try {
            $conn = $this->conexion->getcon();
            $conn->beginTransaction();

            // Validar campos básicos requeridos
            $campos_requeridos = ['titulo', 'autor', 'narrador', 'fechaPublicacion', 
                                'descripcion', 'precio'];
            
            foreach ($campos_requeridos as $campo) {
                if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                    throw new Exception("Campo requerido faltante: $campo");
                }
            }

            // Establecer duración predeterminada si no se proporciona
            $duracion = !empty($_POST['duracion']) ? $_POST['duracion'] : '0:00';

            // Manejar la subida de archivos
            $rutaAudio = '';
            $rutaPortada = '';

            // Procesar archivo de audio
            if (isset($_FILES['rutaAudio']) && $_FILES['rutaAudio']['error'] === UPLOAD_ERR_OK) {
                $audioFileName = basename($_FILES['rutaAudio']['name']);
                $rutaAudio = 'audio/' . $audioFileName;
                $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/mindsound/Project/' . $rutaAudio;
                
                $audioDir = dirname($targetPath);
                if (!file_exists($audioDir)) {
                    mkdir($audioDir, 0777, true);
                }
                
                if (!move_uploaded_file($_FILES['rutaAudio']['tmp_name'], $targetPath)) {
                    throw new Exception("Error al subir el archivo de audio");
                }
            }

            // Procesar imagen de portada
            if (isset($_FILES['rutaPortada']) && $_FILES['rutaPortada']['error'] === UPLOAD_ERR_OK) {
                $portadaFileName = basename($_FILES['rutaPortada']['name']);
                $rutaPortada = 'img/Books/' . $portadaFileName;
                $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/mindsound/Project/' . $rutaPortada;
                
                $portadaDir = dirname($targetPath);
                if (!file_exists($portadaDir)) {
                    mkdir($portadaDir, 0777, true);
                }
                
                if (!move_uploaded_file($_FILES['rutaPortada']['tmp_name'], $targetPath)) {
                    throw new Exception("Error al subir la imagen de portada");
                }
            }

            // Inicializar la variable @id_libro
            $conn->query("SET @id_libro = 0");

            // Preparar y ejecutar el procedimiento almacenado
            $stmt = $conn->prepare("CALL SP_AGREGAR_LIBRO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @id_libro)");
            
            $esGratuito = isset($_POST['esGratuito']) ? 1 : 0;
            
            $stmt->bindParam(1, $_POST['titulo']);
            $stmt->bindParam(2, $_POST['autor']);
            $stmt->bindParam(3, $_POST['narrador']);
            $stmt->bindParam(4, $duracion);
            $stmt->bindParam(5, $_POST['fechaPublicacion']);
            $stmt->bindParam(6, $_POST['descripcion']);
            $stmt->bindParam(7, $rutaAudio);
            $stmt->bindParam(8, $rutaPortada);
            $stmt->bindParam(9, $_POST['precio']);
            $stmt->bindParam(10, $esGratuito);
            
            $stmt->execute();
            $stmt->closeCursor(); // Cerrar el cursor después de la ejecución

            // Obtener el ID del libro recién insertado
            $result = $conn->query("SELECT @id_libro as IDLibro");
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $idLibro = $row['IDLibro'];
            $result->closeCursor(); // Cerrar el cursor del resultado

            // Insertar los géneros seleccionados
            if (isset($_POST['generos']) && is_array($_POST['generos'])) {
                $stmtGenero = $conn->prepare("INSERT INTO LibroGenero (IDLibro, IDGenero) VALUES (?, ?)");
                foreach ($_POST['generos'] as $idGenero) {
                    $stmtGenero->bindParam(1, $idLibro);
                    $stmtGenero->bindParam(2, $idGenero);
                    $stmtGenero->execute();
                    $stmtGenero->closeCursor(); // Cerrar el cursor después de cada inserción
                }
            }

            $conn->commit();
            return json_encode(['status' => 'success', 'message' => 'Libro agregado con éxito']);
            
        } catch (Exception $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
            error_log("Error al agregar libro: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    public function actualizarLibro() {
        try {
            $conn = $this->conexion->getcon();
            $conn->beginTransaction();

            // Manejar la subida de archivos
            $rutaAudio = $_POST['rutaAudio_actual'] ?? ''; // Mantener la ruta actual si no hay nuevo archivo
            $rutaPortada = $_POST['rutaPortada_actual'] ?? ''; // Mantener la ruta actual si no hay nuevo archivo

            // Procesar archivo de audio si se subió uno nuevo
            if (isset($_FILES['rutaAudio']) && $_FILES['rutaAudio']['error'] === UPLOAD_ERR_OK) {
                $audioFileName = basename($_FILES['rutaAudio']['name']);
                $rutaAudio = 'audio/' . $audioFileName;
                $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/mindsound/Project/' . $rutaAudio;
                
                // Asegurarse de que el directorio existe
                $audioDir = dirname($targetPath);
                if (!file_exists($audioDir)) {
                    mkdir($audioDir, 0777, true);
                }
                
                if (!move_uploaded_file($_FILES['rutaAudio']['tmp_name'], $targetPath)) {
                    throw new Exception("Error al subir el archivo de audio");
                }
            }

            // Procesar imagen de portada si se subió una nueva
            if (isset($_FILES['rutaPortada']) && $_FILES['rutaPortada']['error'] === UPLOAD_ERR_OK) {
                $portadaFileName = basename($_FILES['rutaPortada']['name']);
                $rutaPortada = 'img/Books/' . $portadaFileName;
                $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/mindsound/Project/' . $rutaPortada;
                
                // Asegurarse de que el directorio existe
                $portadaDir = dirname($targetPath);
                if (!file_exists($portadaDir)) {
                    mkdir($portadaDir, 0777, true);
                }
                
                if (!move_uploaded_file($_FILES['rutaPortada']['tmp_name'], $targetPath)) {
                    throw new Exception("Error al subir la imagen de portada");
                }
            }

            // Actualizar el libro
            $stmt = $conn->prepare("CALL SP_ACTUALIZAR_LIBRO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $esGratuito = isset($_POST['esGratuito']) ? 1 : 0;
            
            $stmt->bindParam(1, $_POST['idLibro']);
            $stmt->bindParam(2, $_POST['titulo']);
            $stmt->bindParam(3, $_POST['autor']);
            $stmt->bindParam(4, $_POST['narrador']);
            $stmt->bindParam(5, $_POST['duracion']);
            $stmt->bindParam(6, $_POST['fechaPublicacion']);
            $stmt->bindParam(7, $_POST['descripcion']);
            $stmt->bindParam(8, $rutaAudio);
            $stmt->bindParam(9, $rutaPortada);
            $stmt->bindParam(10, $_POST['precio']);
            $stmt->bindParam(11, $esGratuito);
            
            $stmt->execute();
            $stmt->closeCursor();

            // Eliminar géneros anteriores
            $stmtDelete = $conn->prepare("DELETE FROM LibroGenero WHERE IDLibro = ?");
            $stmtDelete->bindParam(1, $_POST['idLibro']);
            $stmtDelete->execute();
            $stmtDelete->closeCursor();

            // Insertar nuevos géneros
            if (isset($_POST['generos']) && is_array($_POST['generos'])) {
                $stmtGenero = $conn->prepare("INSERT INTO LibroGenero (IDLibro, IDGenero) VALUES (?, ?)");
                foreach ($_POST['generos'] as $idGenero) {
                    $stmtGenero->bindParam(1, $_POST['idLibro']);
                    $stmtGenero->bindParam(2, $idGenero);
                    $stmtGenero->execute();
                }
                $stmtGenero->closeCursor();
            }

            $conn->commit();
            return json_encode(['status' => 'success', 'message' => 'Libro actualizado con éxito']);
        } catch (Exception $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
            error_log("Error al actualizar libro: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return json_encode(['status' => 'error', 'message' => 'Error al actualizar el libro: ' . $e->getMessage()]);
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
                return $targetDir . $fileName;
            }
        }
        return false;
    }

    public function obtenerGenerosLibro($idLibro) {
        try {
            $conn = $this->conexion->getcon();
            $stmt = $conn->prepare("CALL SP_OBTENER_GENEROS_LIBRO(?)");
            $stmt->bindParam(1, $idLibro);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener géneros del libro: " . $e->getMessage());
            return [];
        }
    }
}

// Manejo de solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
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
            case 'obtenerGeneros':
                $controlador->obtenerGenerosLibro();
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
                break;
        }
    } catch (Exception $e) {
        error_log("Error en el controlador: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
}
