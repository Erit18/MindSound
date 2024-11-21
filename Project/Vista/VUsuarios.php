<?php
require_once '../Controlador/BD/Conexion.php';

try {
    // Crear una instancia de la clase Conexion
    $conexion = new Conexion();
    // Obtener la conexión
    $conn = $conexion->getcon();

    // Obtener usuarios
    $stmt = $conn->prepare("CALL SP_OBTENER_USUARIOS('')");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$baseUrl = '/mindsound/Project';
?>

<h2>Gestión de Usuarios</h2>

<div class="card-body">
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary" id="btnMostrarFormulario">Agregar Usuario</button>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12" id="tablaUsuarios">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo Electrónico</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Género</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['IDUsuario']; ?></td>
                        <td><?php echo $usuario['Nombre']; ?></td>
                        <td><?php echo $usuario['Apellido']; ?></td>
                        <td><?php echo $usuario['CorreoElectronico']; ?></td>
                        <td><?php echo $usuario['FechaNacimiento']; ?></td>
                        <td><?php echo $usuario['Genero']; ?></td>
                        <td><?php echo $usuario['Rol']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary btnEditar" 
                                data-id="<?php echo $usuario['IDUsuario']; ?>"
                                data-nombre="<?php echo $usuario['Nombre']; ?>"
                                data-apellido="<?php echo $usuario['Apellido']; ?>"
                                data-email="<?php echo $usuario['CorreoElectronico']; ?>"
                                data-fechanacimiento="<?php echo $usuario['FechaNacimiento']; ?>"
                                data-genero="<?php echo $usuario['Genero']; ?>"
                                data-rol="<?php echo $usuario['Rol']; ?>">
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger btnEliminar" data-id="<?php echo $usuario['IDUsuario']; ?>">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuarioLabel">Agregar/Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <input type="hidden" id="idUsuario" name="idUsuario">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
                    </div>
                    <div class="form-group">
                        <label for="genero">Género:</label>
                        <select class="form-control" id="genero" name="genero" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol:</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="">Seleccione...</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Usuario">Usuario</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarUsuario">Guardar Usuario</button>
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
                ¿Está seguro de que desea eliminar este usuario?
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
    let usuarioIdAEliminar;

    $("#btnMostrarFormulario").click(function() {
        $("#idUsuario").val('');
        $("#formUsuario")[0].reset();
        $("#modalUsuarioLabel").text("Agregar Usuario");
        $("#modalUsuario").modal('show');
    });

    $(".btnEditar").click(function() {
        const userId = $(this).data('id');
        $("#idUsuario").val(userId);
        $("#nombre").val($(this).data('nombre'));
        $("#apellido").val($(this).data('apellido'));
        $("#email").val($(this).data('email'));
        $("#fechaNacimiento").val($(this).data('fechanacimiento'));
        $("#genero").val($(this).data('genero'));
        $("#rol").val($(this).data('rol'));
        $("#modalUsuarioLabel").text("Editar Usuario");
        $("#modalUsuario").modal('show');
    });

    $(".btnEliminar").click(function() {
        usuarioIdAEliminar = $(this).data('id');
        $("#modalConfirmarEliminar").modal('show');
    });

    $("#btnGuardarUsuario").click(function() {
        const idUsuario = $("#idUsuario").val();
        const nombre = $("#nombre").val();
        const apellido = $("#apellido").val();
        const email = $("#email").val();
        const fechaNacimiento = $("#fechaNacimiento").val();
        const genero = $("#genero").val();
        const rol = $("#rol").val();
        const password = $("#password").val();

        const accion = idUsuario ? 'editar' : 'agregar';

        $.ajax({
            url: baseUrl + '/Controlador/CUsuarios.php',
            type: 'POST',
            data: {
                accion: accion,
                idUsuario: idUsuario,
                nombre: nombre,
                apellido: apellido,
                email: email,
                fechaNacimiento: fechaNacimiento,
                genero: genero,
                rol: rol,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta del servidor:", response);
                if (response.status === 'success') {
                    alert(accion === 'editar' ? "Usuario actualizado con éxito" : "Usuario agregado con éxito");
                    location.reload();
                } else {
                    alert("Error al " + (accion === 'editar' ? "actualizar" : "agregar") + " el usuario: " + response.message);
                }
                $("#modalUsuario").modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                console.log("Respuesta del servidor:", jqXHR.responseText);
                alert("Error en la solicitud. Por favor, revisa la consola para más detalles.");
                $("#modalUsuario").modal('hide');
            }
        });
    });

    $("#btnConfirmarEliminar").click(function() {
        $.ajax({
            url: baseUrl + '/Controlador/CUsuarios.php',
            type: 'POST',
            data: {
                accion: 'eliminar',
                idUsuario: usuarioIdAEliminar
            },
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta del servidor:", response);
                if (response.status === 'success') {
                    alert("Usuario eliminado con éxito");
                    location.reload();
                } else {
                    alert("Error al eliminar el usuario: " + response.message);
                }
                $("#modalConfirmarEliminar").modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                console.log("Respuesta del servidor:", jqXHR.responseText);
                alert("Error en la solicitud. Por favor, revisa la consola para más detalles.");
                $("#modalConfirmarEliminar").modal('hide');
            }
        });
    });
});
</script>
