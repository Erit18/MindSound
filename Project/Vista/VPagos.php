<?php
require_once '../Controlador/BD/Conexion.php';

try {
    $conexion = new Conexion();
    $conn = $conexion->getcon();

    $stmt = $conn->prepare("CALL SP_OBTENER_PAGOS()");
    $stmt->execute();
    $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$baseUrl = '/Project'; // Ajusta esto para que coincida con la ruta base de tu proyecto
?>

<h2>Gestión de Pagos</h2>

<div class="card-body">
    <div class="row">
        <div class="col-md-12" id="tablaPagos">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Tipo Suscripción</th>
                        <th>Monto</th>
                        <th>Fecha Pago</th>
                        <th>Método Pago</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagos as $pago): ?>
                    <tr>
                        <td><?php echo $pago['IDPago']; ?></td>
                        <td><?php echo $pago['Nombre'] . ' ' . $pago['Apellido']; ?></td>
                        <td><?php echo $pago['TipoSuscripcion']; ?></td>
                        <td><?php echo $pago['Monto']; ?></td>
                        <td><?php echo $pago['FechaPago']; ?></td>
                        <td><?php echo $pago['MetodoPago']; ?></td>
                        <td><?php echo $pago['EstadoPago']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary btnEditar" 
                                data-id="<?php echo $pago['IDPago']; ?>"
                                data-monto="<?php echo $pago['Monto']; ?>"
                                data-metodo="<?php echo $pago['MetodoPago']; ?>"
                                data-estado="<?php echo $pago['EstadoPago']; ?>">
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger btnEliminar" data-id="<?php echo $pago['IDPago']; ?>">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para editar pago -->
<div class="modal fade" id="modalPago" tabindex="-1" role="dialog" aria-labelledby="modalPagoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPagoLabel">Editar Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPago">
                    <input type="hidden" id="idPago" name="idPago">
                    <div class="form-group">
                        <label for="monto">Monto:</label>
                        <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                    </div>
                    <div class="form-group">
                        <label for="metodoPago">Método de Pago:</label>
                        <input type="text" class="form-control" id="metodoPago" name="metodoPago" required>
                    </div>
                    <div class="form-group">
                        <label for="estadoPago">Estado del Pago:</label>
                        <select class="form-control" id="estadoPago" name="estadoPago" required>
                            <option value="Completado">Completado</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Fallido">Fallido</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarPago">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let pagoIdAEliminar;

    $(".btnEditar").click(function() {
        const pagoId = $(this).data('id');
        $("#idPago").val(pagoId);
        $("#monto").val($(this).data('monto'));
        $("#metodoPago").val($(this).data('metodo'));
        $("#estadoPago").val($(this).data('estado'));
        $("#modalPago").modal('show');
    });

    $(".btnEliminar").click(function() {
        pagoIdAEliminar = $(this).data('id');
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
                eliminarPago(pagoIdAEliminar);
            }
        });
    });

    $("#btnGuardarPago").click(function() {
        const idPago = $("#idPago").val();
        const monto = $("#monto").val();
        const metodoPago = $("#metodoPago").val();
        const estadoPago = $("#estadoPago").val();

        $.ajax({
            url: baseUrl + '/Controlador/CPagos.php',
            type: 'POST',
            data: {
                accion: 'editar',
                idPago: idPago,
                monto: monto,
                metodoPago: metodoPago,
                estadoPago: estadoPago
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pago actualizado',
                        text: 'El pago se ha actualizado con éxito.',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "Error al actualizar el pago: " + response.message,
                    });
                }
                $("#modalPago").modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la solicitud. Por favor, revisa la consola para más detalles.',
                });
                $("#modalPago").modal('hide');
            }
        });
    });

    function eliminarPago(idPago) {
        $.ajax({
            url: baseUrl + '/Controlador/CPagos.php',
            type: 'POST',
            data: {
                accion: 'eliminar',
                idPago: idPago
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'El pago se ha eliminado con éxito.',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "Error al eliminar el pago: " + response.message,
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
});
</script>
