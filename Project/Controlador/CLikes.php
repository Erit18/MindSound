<?php
require_once __DIR__ . '/BD/Conexion.php';

class CLikes {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function guardarLibro($idUsuario, $idLibro) {
        try {
            $conn = $this->conexion->getcon();
            $stmt = $conn->prepare("CALL SP_GUARDAR_LIBRO(?, ?)");
            $stmt->bindParam(1, $idUsuario);
            $stmt->bindParam(2, $idLibro);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al guardar libro: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarLibroGuardado($idUsuario, $idLibro) {
        try {
            $conn = $this->conexion->getcon();
            $stmt = $conn->prepare("CALL SP_ELIMINAR_LIBRO_GUARDADO(?, ?)");
            $stmt->bindParam(1, $idUsuario);
            $stmt->bindParam(2, $idLibro);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar libro guardado: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerLibrosGuardados($idUsuario) {
        try {
            $conn = $this->conexion->getcon();
            $stmt = $conn->prepare("CALL SP_OBTENER_LIBROS_GUARDADOS(?)");
            $stmt->bindParam(1, $idUsuario);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener libros guardados: " . $e->getMessage());
            return [];
        }
    }

    public function verificarLibroGuardado($idUsuario, $idLibro) {
        try {
            $conn = $this->conexion->getcon();
            $stmt = $conn->prepare("SELECT COUNT(*) FROM LibrosGuardados WHERE IDUsuario = ? AND IDLibro = ?");
            $stmt->bindParam(1, $idUsuario);
            $stmt->bindParam(2, $idLibro);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Error al verificar libro guardado: " . $e->getMessage());
            return false;
        }
    }
} 