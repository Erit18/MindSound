<?php
require_once '../Controlador/BD/Conexion.php';

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();

    $stmt = $conn->prepare("CALL SP_OBTENER_SUSCRIPCIONES()");
    $stmt->execute();
    $suscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$baseUrl = '/Project'; // Ajusta esto para que coincida con la ruta base de tu proyecto
?>

<h2>Gestión de Suscripciones</h2>

<div class="card-body">
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary" id="btnMostrarFormulario">Agregar Suscripción</button>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12" id="tablaSuscripciones">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Tipo Suscripción</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suscripciones as $suscripcion): ?>
                    <tr>
                        <td><?php echo $suscripcion['IDSuscripcion']; ?></td>
                        <td><?php echo $suscripcion['Nombre'] . ' ' . $suscripcion['Apellido']; ?></td>
                        <td><?php echo $suscripcion['TipoSuscripcion']; ?></td>
                        <td><?php echo $suscripcion['FechaInicio']; ?></td>
                        <td><?php echo $suscripcion['FechaFin']; ?></td>
                        <td><?php echo $suscripcion['EstadoSuscripcion']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary btnEditar" 
                                data-id="<?php echo $suscripcion['IDSuscripcion']; ?>"
                                data-usuario="<?php echo $suscripcion['IDUsuario']; ?>"
                                data-tipo="<?php echo $suscripcion['TipoSuscripcion']; ?>"
                                data-inicio="<?php echo $suscripcion['FechaInicio']; ?>"
                                data-fin="<?php echo $suscripcion['FechaFin']; ?>"
                                data-estado="<?php echo $suscripcion['EstadoSuscripcion']; ?>">
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger btnEliminar" data-id="<?php echo $suscripcion['IDSuscripcion']; ?>">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar suscripción -->
<div class="modal fade" id="modalSuscripcion" tabindex="-1" role="dialog" aria-labelledby="modalSuscripcionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSuscripcionLabel">Agregar/Editar Suscripción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSuscripcion">
                    <input type="hidden" id="idSuscripcion" name="idSuscripcion">
                    <div class="form-group">
                        <label for="idUsuario">Usuario:</label>
                        <select class="form-control" id="idUsuario" name="idUsuario" required>
                            <!-- Opciones de usuarios se cargarán dinámicamente -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipoSuscripcion">Tipo de Suscripción:</label>
                        <select class="form-control" id="tipoSuscripcion" name="tipoSuscripcion" required>
                            <option value="Básica">Básica</option>
                            <option value="Normal">Normal</option>
                            <option value="Premium">Premium</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de Inicio:</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaFin">Fecha de Fin:</label>
                        <input type="date" class="form-control" id="fechaFin" name="fechaFin" required>
                    </div>
                    <div class="form-group">
                        <label for="estadoSuscripcion">Estado de Suscripción:</label>
                        <select class="form-control" id="estadoSuscripcion" name="estadoSuscripcion" required>
                            <option value="Activa">Activa</option>
                            <option value="Cancelada">Cancelada</option>
                            <option value="Expirada">Expirada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarSuscripcion">Guardar Suscripción</button>
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
                ¿Está seguro de que desea eliminar esta suscripción?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
var baseUrl = '<?php echo $baseUrl; ?>';

$(document).ready(function() {
    let suscripcionIdAEliminar;

    // Cargar usuarios al abrir el modal
    $("#btnMostrarFormulario").click(function() {
        cargarUsuarios();
        $("#idSuscripcion").val('');
        $("#formSuscripcion")[0].reset();
        $("#modalSuscripcionLabel").text("Agregar Suscripción");
        $("#modalSuscripcion").modal('show');
    });

    $(".btnEditar").click(function() {
        const suscripcionId = $(this).data('id');
        $("#idSuscripcion").val(suscripcionId);
        $("#idUsuario").val($(this).data('usuario'));
        $("#tipoSuscripcion").val($(this).data('tipo'));
        $("#fechaInicio").val($(this).data('inicio'));
        $("#fechaFin").val($(this).data('fin'));
        $("#estadoSuscripcion").val($(this).data('estado'));
        $("#modalSuscripcionLabel").text("Editar Suscripción");
        cargarUsuarios();
        $("#modalSuscripcion").modal('show');
    });

    $(".btnEliminar").click(function() {
        suscripcionIdAEliminar = $(this).data('id');
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarSuscripcion(suscripcionIdAEliminar);
            }
        });
    });

    $("#btnGuardarSuscripcion").click(function() {
        const idSuscripcion = $("#idSuscripcion").val();
        const idUsuario = $("#idUsuario").val();
        const tipoSuscripcion = $("#tipoSuscripcion").val();
        const fechaInicio = $("#fechaInicio").val();
        const fechaFin = $("#fechaFin").val();
        const estadoSuscripcion = $("#estadoSuscripcion").val();

        const accion = idSuscripcion ? 'editar' : 'agregar';

        $.ajax({
            url: baseUrl + '/Controlador/CSuscripciones.php',
            type: 'POST',
            data: {
                accion: accion,
                idSuscripcion: idSuscripcion,
                idUsuario: idUsuario,
                tipoSuscripcion: tipoSuscripcion,
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
                estadoSuscripcion: estadoSuscripcion
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: accion === 'editar' ? 'Suscripción actualizada' : 'Suscripción agregada',
                        text: accion === 'editar' ? 'La suscripción se ha actualizado con éxito.' : 'La suscripción se ha agregado con éxito.',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "Error al " + (accion === 'editar' ? "actualizar" : "agregar") + " la suscripción: " + response.message,
                    });
                }
                $("#modalSuscripcion").modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la solicitud. Por favor, revisa la consola para más detalles.',
                });
                $("#modalSuscripcion").modal('hide');
            }
        });
    });

    function eliminarSuscripcion(idSuscripcion) {
        $.ajax({
            url: baseUrl + '/Controlador/CSuscripciones.php',
            type: 'POST',
            data: {
                accion: 'eliminar',
                idSuscripcion: idSuscripcion
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'La suscripción se ha eliminado con éxito.',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "Error al eliminar la suscripción: " + response.message,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la solicitud. Por favor, revisa la consola para más detalles.',
                });
            }
        });
    }

    function cargarUsuarios() {
        $.ajax({
            url: baseUrl + '/Controlador/CSuscripciones.php',
            type: 'GET',
            data: { accion: 'obtenerUsuarios' },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const selectUsuario = $("#idUsuario");
                    selectUsuario.empty();
                    response.usuarios.forEach(function(usuario) {
                        selectUsuario.append($('<option>', {
                            value: usuario.IDUsuario,
                            text: usuario.Nombre + ' ' + usuario.Apellido
                        }));
                    });
                } else {
                    console.error("Error al cargar usuarios:", response.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los usuarios.',
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar usuarios. Por favor, revisa la consola para más detalles.',
                });
            }
        });
    }
});
</script>