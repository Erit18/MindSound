<?php
require_once '../Controlador/CLibros.php';

// Obtener libros
$controladorLibros = new CLibros();
$libros = $controladorLibros->obtenerLibros();

// Ajusta esto según la estructura de tu proyecto
$baseUrl = '/mindsound/Project';
?>

<!-- Mantén todo el HTML igual, pero elimina los scripts del final -->
<h2>Gestión de Libros</h2>

<div class="card-body">
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary" id="btnMostrarFormulario">Agregar Nuevo Libro</button>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12" id="tablaLibros">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Narrador</th>
                        <th>Duración</th>
                        <th>Descripción</th>
                        <th>Ruta Audio</th>
                        <th>Portada</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($libros as $libro): ?>
                    <tr>
                        <td><?php echo $libro['IDLibro']; ?></td>
                        <td><?php echo $libro['Titulo']; ?></td>
                        <td><?php echo $libro['Autor']; ?></td>
                        <td><?php echo $libro['Narrador']; ?></td>
                        <td><?php echo $libro['Duracion']; ?></td>
                        <td><?php echo substr($libro['Descripcion'], 0, 50) . '...'; ?></td>
                        <td><?php echo basename($libro['RutaAudio']); ?></td>
                        <td>
                            <?php if (!empty($libro['RutaPortada'])): ?>
                                <?php
                                $rutaCompleta = $baseUrl . '/' . $libro['RutaPortada'];
                                $rutaRelativa = str_replace($_SERVER['DOCUMENT_ROOT'], '', $rutaCompleta);
                                ?>
                                <img src="<?php echo $rutaRelativa; ?>" alt="Portada" style="width: 50px; height: auto;">
                            <?php else: ?>
                                Sin portada
                            <?php endif; ?>
                        </td>
                        <td><?php echo $libro['Precio']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary btnEditar" 
                                data-id="<?php echo $libro['IDLibro']; ?>"
                                data-titulo="<?php echo htmlspecialchars($libro['Titulo']); ?>"
                                data-autor="<?php echo htmlspecialchars($libro['Autor']); ?>"
                                data-narrador="<?php echo htmlspecialchars($libro['Narrador']); ?>"
                                data-duracion="<?php echo $libro['Duracion']; ?>"
                                data-fechapublicacion="<?php echo $libro['FechaPublicacion']; ?>"
                                data-descripcion="<?php echo htmlspecialchars($libro['Descripcion']); ?>"
                                data-precio="<?php echo $libro['Precio']; ?>"
                                data-esgratuito="<?php echo $libro['EsGratuito']; ?>"
                                data-rutaaudio="<?php echo htmlspecialchars($libro['RutaAudio']); ?>"
                                data-rutaportada="<?php echo htmlspecialchars($libro['RutaPortada']); ?>">
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger btnEliminar" 
                                data-id="<?php echo $libro['IDLibro']; ?>" 
                                data-titulo="<?php echo htmlspecialchars($libro['Titulo']); ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar libro -->
<div class="modal fade" id="modalLibro" tabindex="-1" role="dialog" aria-labelledby="modalLibroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLibroLabel">Agregar/Editar Libro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formLibro" enctype="multipart/form-data">
                    <input type="hidden" id="idLibro" name="idLibro">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="autor">Autor:</label>
                        <input type="text" class="form-control" id="autor" name="autor" required>
                    </div>
                    <div class="form-group">
                        <label for="narrador">Narrador:</label>
                        <input type="text" class="form-control" id="narrador" name="narrador">
                    </div>
                    <div class="form-group">
                        <label for="rutaAudio">Audio del libro (MP3)</label>
                        <input type="file" class="form-control-file" id="rutaAudio" name="rutaAudio" accept=".mp3">
                        <small class="form-text text-muted">Seleccione un archivo de audio en formato MP3</small>
                    </div>
                    <div class="form-group">
                        <label for="duracion">Duración</label>
                        <input type="text" class="form-control" id="duracion" name="duracion" readonly>
                        <small class="form-text text-muted">La duración se calculará automáticamente al seleccionar el archivo de audio</small>
                    </div>
                    <div class="form-group">
                        <label for="fechaPublicacion">Fecha de Publicación:</label>
                        <input type="date" class="form-control" id="fechaPublicacion" name="fechaPublicacion" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="rutaPortada">Imagen de Portada</label>
                        <input type="file" class="form-control-file" id="rutaPortada" name="rutaPortada" accept="image/*">
                        <small class="form-text text-muted">Seleccione una imagen para la portada</small>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="esGratuito" name="esGratuito">
                        <label class="form-check-label" for="esGratuito">Es gratuito</label>
                    </div>
                    <div class="form-group">
                        <label for="generos">Géneros:</label>
                        <select class="form-control select2" id="generos" name="generos[]" multiple="multiple" required>
                            <?php
                            try {
                                // Usar el controlador existente
                                $controladorLibros = new CLibros();
                                $conn = $controladorLibros->conexion->getcon();
                                
                                // Debug: Imprimir la conexión
                                var_dump($conn);
                                
                                // Obtener todos los géneros
                                $stmt = $conn->prepare("SELECT IDGenero, NombreGenero FROM Generos ORDER BY NombreGenero");
                                $stmt->execute();
                                $generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                // Debug: Imprimir los géneros
                                var_dump($generos);
                                
                                if (empty($generos)) {
                                    echo "<option value=''>No se encontraron géneros</option>";
                                } else {
                                    foreach ($generos as $genero) {
                                        echo "<option value='" . $genero['IDGenero'] . "'>" . htmlspecialchars($genero['NombreGenero']) . "</option>";
                                    }
                                }
                            } catch (PDOException $e) {
                                error_log("Error al cargar géneros: " . $e->getMessage());
                                echo "<option value=''>Error al cargar géneros: " . $e->getMessage() . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarLibro">Guardar Libro</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" role="dialog" aria-labelledby="modalConfirmarEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmarEliminarLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar este libro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensajes -->
<div class="modal fade" id="modalMensaje" tabindex="-1" role="dialog" aria-labelledby="modalMensajeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMensajeLabel">Mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalMensajeContenido">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- Solo mantén las dependencias necesarias -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
