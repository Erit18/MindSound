<?php
require_once '../Controlador/CLibros.php';

// Obtener libros
$controladorLibros = new CLibros();
$libros = $controladorLibros->obtenerLibros();

// Ajusta esto según la estructura de tu proyecto
$baseUrl = '/Project';

// Si estás usando un servidor virtual, podrías necesitar esto en su lugar:
// $baseUrl = ''
?>


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
                                data-titulo="<?php echo $libro['Titulo']; ?>"
                                data-autor="<?php echo $libro['Autor']; ?>"
                                data-narrador="<?php echo $libro['Narrador']; ?>"
                                data-duracion="<?php echo $libro['Duracion']; ?>"
                                data-fechapublicacion="<?php echo $libro['FechaPublicacion']; ?>"
                                data-descripcion="<?php echo $libro['Descripcion']; ?>"
                                data-precio="<?php echo $libro['Precio']; ?>"
                                data-esgratuito="<?php echo $libro['EsGratuito']; ?>">
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger btnEliminar" data-id="<?php echo $libro['IDLibro']; ?>">Eliminar</button>
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
                        <input type="file" class="form-control-file" id="rutaAudio" name="rutaAudio" accept=".mp3" required>
                    </div>
                    <div class="form-group">
                        <label for="duracion">Duración</label>
                        <input type="text" class="form-control" id="duracion" name="duracion" readonly>
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
                        <label for="rutaPortada">Imagen de Portada:</label>
                        <input type="file" class="form-control-file" id="rutaPortada" name="rutaPortada" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="esGratuito" name="esGratuito">
                        <label class="form-check-label" for="esGratuito">Es gratuito</label>
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

<script>
var baseUrl = '<?php echo $baseUrl; ?>';

$(document).ready(function() {
    let libroIdAEliminar;

    function mostrarMensaje(mensaje) {
        $("#modalMensajeContenido").text(mensaje);
        $("#modalMensaje").modal('show');
    }

    $("#btnMostrarFormulario").click(function() {
        $("#idLibro").val('');
        $("#formLibro")[0].reset();
        $("#modalLibroLabel").text("Agregar Nuevo Libro");
        $("#modalLibro").modal('show');
    });

    $(".btnEditar").click(function() {
        const libroId = $(this).data('id');
        $("#idLibro").val(libroId);
        $("#titulo").val($(this).data('titulo'));
        $("#autor").val($(this).data('autor'));
        $("#narrador").val($(this).data('narrador'));
        $("#duracion").val($(this).data('duracion'));
        $("#fechaPublicacion").val($(this).data('fechapublicacion'));
        $("#descripcion").val($(this).data('descripcion'));
        $("#precio").val($(this).data('precio'));
        $("#esGratuito").prop('checked', $(this).data('esgratuito') == 1);
        $("#modalLibroLabel").text("Editar Libro");
        $("#modalLibro").modal('show');
    });

    $(".btnEliminar").click(function() {
        libroIdAEliminar = $(this).data('id');
        $("#modalConfirmarEliminar").modal('show');
    });

    $("#btnGuardarLibro").click(function() {
        var formData = new FormData($("#formLibro")[0]);
        const idLibro = $("#idLibro").val();
        formData.append('accion', idLibro ? 'actualizar' : 'agregar');

        $.ajax({
            url: baseUrl + '/Controlador/CLibros.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                $("#modalLibro").modal('hide');
                if (response.status === 'success') {
                    mostrarMensaje(idLibro ? "Libro actualizado con éxito" : "Libro agregado con éxito");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje("Error al " + (idLibro ? "actualizar" : "agregar") + " el libro: " + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#modalLibro").modal('hide');
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                console.log("Respuesta del servidor:", jqXHR.responseText);
                mostrarMensaje("Error en la solicitud. Por favor, revisa la consola para más detalles.");
            }
        });
    });

    $("#btnConfirmarEliminar").click(function() {
        $.ajax({
            url: baseUrl + '/Controlador/CLibros.php',
            type: 'POST',
            data: {
                accion: 'eliminar',
                idLibro: libroIdAEliminar
            },
            dataType: 'json',
            success: function(response) {
                $("#modalConfirmarEliminar").modal('hide');
                if (response.status === 'success') {
                    mostrarMensaje("Libro eliminado con éxito");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje("Error al eliminar el libro: " + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#modalConfirmarEliminar").modal('hide');
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                console.log("Respuesta del servidor:", jqXHR.responseText);
                mostrarMensaje("Error en la solicitud. Por favor, revisa la consola para más detalles.");
            }
        });
    });
});

document.getElementById('rutaAudio').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file && file.type === "audio/mpeg") {
        var audio = new Audio();
        audio.onloadedmetadata = function() {
            var duration = audio.duration;
            var minutes = Math.floor(duration / 60);
            var seconds = Math.floor(duration % 60);
            document.getElementById('duracion').value = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        };
        audio.src = URL.createObjectURL(file);
    } else {
        alert('Por favor, seleccione un archivo MP3 válido.');
        this.value = ''; // Limpiar el input
        document.getElementById('duracion').value = '';
    }
});

// Modificar el evento de envío del formulario
document.getElementById('formLibro').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({
        url: 'Controlador/CLibros.php?action=agregar',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // Manejar la respuesta
            console.log(response);
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Libro agregado correctamente',
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },
        error: function(xhr, status, error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo agregar el libro. Por favor, intente de nuevo.',
            });
        }
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jsmediatags/3.9.5/jsmediatags.min.js"></script>