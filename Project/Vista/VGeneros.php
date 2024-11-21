<?php
require_once '../Controlador/BD/Conexion.php';

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();

    $stmt = $conn->prepare("CALL SP_OBTENER_GENEROS()");
    $stmt->execute();
    $generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$baseUrl = '/mindsound/Project';
?>


<h2>Gestión de Géneros</h2>

<div class="card-body">
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary" id="btnMostrarFormulario">Agregar Género</button>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12" id="tablaGeneros">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($generos as $genero): ?>
                    <tr>
                        <td><?php echo $genero['IDGenero']; ?></td>
                        <td><?php echo $genero['NombreGenero']; ?></td>
                        <td><?php echo $genero['Descripcion']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary btnEditar" 
                                data-id="<?php echo $genero['IDGenero']; ?>"
                                data-nombre="<?php echo $genero['NombreGenero']; ?>"
                                data-descripcion="<?php echo $genero['Descripcion']; ?>">
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger btnEliminar" data-id="<?php echo $genero['IDGenero']; ?>">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar género -->
<div class="modal fade" id="modalGenero" tabindex="-1" role="dialog" aria-labelledby="modalGeneroLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGeneroLabel">Agregar/Editar Género</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formGenero">
                    <input type="hidden" id="idGenero" name="idGenero">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarGenero">Guardar Género</button>
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
                ¿Está seguro de que desea eliminar este género?
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
    let generoIdAEliminar;

    function mostrarMensaje(mensaje) {
        $("#modalMensajeContenido").text(mensaje);
        $("#modalMensaje").modal('show');
    }

    $("#btnMostrarFormulario").click(function() {
        $("#idGenero").val('');
        $("#formGenero")[0].reset();
        $("#modalGeneroLabel").text("Agregar Género");
        $("#modalGenero").modal('show');
    });

    $(".btnEditar").click(function() {
        const generoId = $(this).data('id');
        $("#idGenero").val(generoId);
        $("#nombre").val($(this).data('nombre'));
        $("#descripcion").val($(this).data('descripcion'));
        $("#modalGeneroLabel").text("Editar Género");
        $("#modalGenero").modal('show');
    });

    $(".btnEliminar").click(function() {
        generoIdAEliminar = $(this).data('id');
        $("#modalConfirmarEliminar").modal('show');
    });

    $("#btnGuardarGenero").click(function() {
        const idGenero = $("#idGenero").val();
        const nombre = $("#nombre").val();
        const descripcion = $("#descripcion").val();

        const accion = idGenero ? 'editar' : 'agregar';

        $.ajax({
            url: baseUrl + '/Controlador/CGeneros.php',
            type: 'POST',
            data: {
                accion: accion,
                idGenero: idGenero,
                nombre: nombre,
                descripcion: descripcion
            },
            dataType: 'json',
            success: function(response) {
                $("#modalGenero").modal('hide');
                if (response.status === 'success') {
                    mostrarMensaje(accion === 'editar' ? "Género actualizado con éxito" : "Género agregado con éxito");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje("Error al " + (accion === 'editar' ? "actualizar" : "agregar") + " el género: " + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#modalGenero").modal('hide');
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                console.log("Respuesta del servidor:", jqXHR.responseText);
                mostrarMensaje("Error en la solicitud. Por favor, revisa la consola para más detalles.");
            }
        });
    });

    $("#btnConfirmarEliminar").click(function() {
        $.ajax({
            url: baseUrl + '/Controlador/CGeneros.php',
            type: 'POST',
            data: {
                accion: 'eliminar',
                idGenero: generoIdAEliminar
            },
            dataType: 'json',
            success: function(response) {
                $("#modalConfirmarEliminar").modal('hide');
                if (response.status === 'success') {
                    mostrarMensaje("Género eliminado con éxito");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje("Error al eliminar el género: " + response.message);
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
</script>