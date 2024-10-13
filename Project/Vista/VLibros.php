<?php
require_once '../Controlador/CLibros.php';

// Obtener libros
$controladorLibros = new CLibros();
$libros = $controladorLibros->obtenerLibros();
?>

<h2>Gestión de Libros</h2>

<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalAgregarLibro">Agregar Nuevo Libro</button>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Narrador</th>
            <th>Duración</th>
            <th>Descripción</th>
            <th>Ruta Audio</th>
            <th>Ruta Portada</th>
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
                <?php if ($libro['RutaPortada']): ?>
                    <img src="<?php echo $libro['RutaPortada']; ?>" alt="Portada" style="width: 50px; height: auto;">
                <?php else: ?>
                    Sin portada
                <?php endif; ?>
            </td>
            <td><?php echo $libro['Precio']; ?></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editarLibro(<?php echo $libro['IDLibro']; ?>)">Editar</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarLibro(<?php echo $libro['IDLibro']; ?>)">Eliminar</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal para Agregar Nuevo Libro -->
<div class="modal fade" id="modalAgregarLibro" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLibroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarLibroLabel">Agregar Nuevo Libro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formLibro" enctype="multipart/form-data">
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
                        <label for="duracion">Duración:</label>
                        <input type="time" class="form-control" id="duracion" name="duracion">
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
                        <label for="rutaAudio">Archivo de Audio:</label>
                        <input type="file" class="form-control-file" id="rutaAudio" name="rutaAudio" accept="audio/*">
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
                <button type="button" class="btn btn-primary" onclick="agregarLibro()">Agregar Libro</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Libro -->
<div class="modal fade" id="modalEditarLibro" tabindex="-1" role="dialog" aria-labelledby="modalEditarLibroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLibroLabel">Editar Libro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarLibro" enctype="multipart/form-data">
                    <input type="hidden" id="editIdLibro" name="idLibro">
                    <div class="form-group">
                        <label for="editTitulo">Título:</label>
                        <input type="text" class="form-control" id="editTitulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="editAutor">Autor:</label>
                        <input type="text" class="form-control" id="editAutor" name="autor" required>
                    </div>
                    <div class="form-group">
                        <label for="editNarrador">Narrador:</label>
                        <input type="text" class="form-control" id="editNarrador" name="narrador">
                    </div>
                    <div class="form-group">
                        <label for="editDuracion">Duración:</label>
                        <input type="time" class="form-control" id="editDuracion" name="duracion">
                    </div>
                    <div class="form-group">
                        <label for="editFechaPublicacion">Fecha de Publicación:</label>
                        <input type="date" class="form-control" id="editFechaPublicacion" name="fechaPublicacion" required>
                    </div>
                    <div class="form-group">
                        <label for="editDescripcion">Descripción:</label>
                        <textarea class="form-control" id="editDescripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editRutaAudio">Archivo de Audio:</label>
                        <input type="file" class="form-control-file" id="editRutaAudio" name="rutaAudio" accept="audio/*">
                    </div>
                    <div class="form-group">
                        <label for="editRutaPortada">Imagen de Portada:</label>
                        <input type="file" class="form-control-file" id="editRutaPortada" name="rutaPortada" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="editPrecio">Precio:</label>
                        <input type="number" step="0.01" class="form-control" id="editPrecio" name="precio" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editEsGratuito" name="esGratuito">
                        <label class="form-check-label" for="editEsGratuito">Es gratuito</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="actualizarLibro()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
function agregarLibro() {
    var formData = new FormData(document.getElementById('formLibro'));
    formData.append('accion', 'agregar');

    $.ajax({
        url: 'Controlador/CLibros.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
                $('#modalAgregarLibro').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud:', textStatus, errorThrown);
            alert('Error en la solicitud: ' + textStatus);
        }
    });
}

function editarLibro(idLibro) {
    $.ajax({
        url: 'Controlador/CLibros.php',
        type: 'POST',
        data: {
            accion: 'obtener',
            idLibro: idLibro
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var libro = response.data;
                $('#editIdLibro').val(libro.IDLibro);
                $('#editTitulo').val(libro.Titulo);
                $('#editAutor').val(libro.Autor);
                $('#editNarrador').val(libro.Narrador);
                $('#editDuracion').val(libro.Duracion);
                $('#editFechaPublicacion').val(libro.FechaPublicacion);
                $('#editDescripcion').val(libro.Descripcion);
                $('#editPrecio').val(libro.Precio);
                $('#editEsGratuito').prop('checked', libro.EsGratuito == 1);
                $('#modalEditarLibro').modal('show');
            } else {
                alert('Error al obtener datos del libro: ' + response.message);
            }
        },
        error: function() {
            alert('Error en la solicitud');
        }
    });
}

function actualizarLibro() {
    var formData = new FormData(document.getElementById('formEditarLibro'));
    formData.append('accion', 'actualizar');

    $.ajax({
        url: 'Controlador/CLibros.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
                $('#modalEditarLibro').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud:', textStatus, errorThrown);
            alert('Error en la solicitud: ' + textStatus);
        }
    });
}

function eliminarLibro(idLibro) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esta acción",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'Controlador/CLibros.php',
                type: 'POST',
                data: {
                    accion: 'eliminar',
                    idLibro: idLibro
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire(
                            '¡Eliminado!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error en la solicitud:', textStatus, errorThrown);
                    Swal.fire(
                        'Error',
                        'Hubo un problema al procesar la solicitud',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
