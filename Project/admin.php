<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: intranet.php");
    exit();
}

$baseUrl = '/mindsound/Project';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MindSound | Administrador</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="Vista/plugin/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="Vista/plugin/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="admin.php" class="nav-link">Inicio</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="admin.php" class="brand-link">
                <!-- Espacio reservado para el logo personalizado -->
                <img src="Vista/plugin/dist/img/logo.png" alt="MindSound Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">MindSound</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="Vista/plugin/dist/img/usuario.webp" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Administrador</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Usuarios
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" onclick="getClick('VUsuarios.php')" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Gestionar Usuarios</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Libros
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" onclick="getClick('VLibros.php')" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Gestionar Libros</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>
                                    Géneros
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" onclick="getClick('VGeneros.php')" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Gestionar Géneros</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-credit-card"></i>
                                <p>
                                    Suscripciones
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" onclick="getClick('VSuscripciones.php')" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Gestionar Suscripciones</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-money-bill-wave"></i>
                                <p>
                                    Pagos
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" onclick="getClick('VPagos.php')" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Gestionar Pagos</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>CRUD - Libros</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item active">CRUD - Libros</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gestión de Libros</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="TbBody">
                        <!-- El contenido de la gestión de libros se cargará aquí -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        Footer
                    </div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.2.0
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="Vista/plugin/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="Vista/plugin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="Vista/plugin/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="Vista/plugin/dist/js/demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var baseUrl = '<?php echo $baseUrl; ?>';

        function getClick(link) {
            console.log("Cargando:", link);
            $.ajax({
                url: "Vista/" + link,
                type: 'get',
                success: function(response) {
                    $("#TbBody").html(response);
                    // Reinicializar los componentes después de cargar el contenido
                    if (link === 'VLibros.php') {
                        initializeLibrosComponents();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar la página:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la página. Por favor, intente de nuevo más tarde.',
                    });
                }
            });
        }

        function initializeLibrosComponents() {
            console.log("Inicializando componentes de Libros");
            
            // Inicializar Select2
            $('.select2').select2({
                placeholder: "Selecciona los géneros",
                allowClear: true,
                width: '100%'
            });

            // Evento para mostrar el formulario de nuevo libro
            $(document).on('click', "#btnMostrarFormulario", function() {
                console.log("Botón Agregar Nuevo Libro clickeado");
                $("#idLibro").val('');
                $("#formLibro")[0].reset();
                $('.select2').val(null).trigger('change');
                $("#modalLibroLabel").text("Agregar Nuevo Libro");
                $("#modalLibro").modal('show');
            });

            // Evento para editar libro
            $(document).on('click', ".btnEditar", function() {
                console.log("Botón Editar clickeado");
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
                
                // Obtener los géneros del libro
                $.ajax({
                    url: baseUrl + '/Controlador/CLibros.php',
                    type: 'POST',
                    data: {
                        accion: 'obtenerGeneros',
                        idLibro: libroId
                    },
                    success: function(response) {
                        console.log("Respuesta de géneros:", response);
                        try {
                            response = typeof response === 'string' ? JSON.parse(response) : response;
                            if (response.status === 'success') {
                                $('#generos').val(response.generos).trigger('change');
                            }
                        } catch (e) {
                            console.error("Error al procesar géneros:", e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener géneros:", error);
                    }
                });

                // Guardar las rutas actuales en campos ocultos
                $("#formLibro").append('<input type="hidden" name="rutaAudio_actual" value="' + $(this).data('rutaaudio') + '">');
                $("#formLibro").append('<input type="hidden" name="rutaPortada_actual" value="' + $(this).data('rutaportada') + '">');

                $("#modalLibro").modal('show');
            });

            // Evento para guardar libro
            $(document).on('click', "#btnGuardarLibro", function() {
                console.log("Botón Guardar clickeado");
                var formData = new FormData($("#formLibro")[0]);
                const idLibro = $("#idLibro").val();
                formData.append('accion', idLibro ? 'actualizar' : 'agregar');

                $.ajax({
                    url: baseUrl + '/Controlador/CLibros.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log("Respuesta del servidor:", response);
                        $("#modalLibro").modal('hide');
                        try {
                            response = typeof response === 'string' ? JSON.parse(response) : response;
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: idLibro ? "Libro actualizado con éxito" : "Libro agregado con éxito",
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        } catch (e) {
                            console.error("Error al procesar respuesta:", e);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al procesar la respuesta del servidor'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error AJAX:", textStatus, errorThrown);
                        $("#modalLibro").modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error en la solicitud. Por favor, revisa la consola para más detalles.'
                        });
                    }
                });
            });

            // Variable para almacenar el ID del libro a eliminar
            let libroIdAEliminar;
            let libroTituloAEliminar;

            // Evento para eliminar libro
            $(document).on('click', ".btnEliminar", function() {
                console.log("Botón Eliminar clickeado");
                libroIdAEliminar = $(this).data('id');
                libroTituloAEliminar = $(this).data('titulo');
                
                // Usar SweetAlert2 para la confirmación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Deseas eliminar el libro "${libroTituloAEliminar}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realizar la eliminación
                        $.ajax({
                            url: baseUrl + '/Controlador/CLibros.php',
                            type: 'POST',
                            data: {
                                accion: 'eliminar',
                                idLibro: libroIdAEliminar
                            },
                            success: function(response) {
                                console.log("Respuesta del servidor:", response);
                                try {
                                    response = typeof response === 'string' ? JSON.parse(response) : response;
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Éxito',
                                            text: 'Libro eliminado correctamente',
                                            timer: 1500
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message || 'Error al eliminar el libro'
                                        });
                                    }
                                } catch (e) {
                                    console.error("Error al procesar respuesta:", e);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error al procesar la respuesta del servidor'
                                    });
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error("Error AJAX:", textStatus, errorThrown);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error en la solicitud. Por favor, revisa la consola para más detalles.'
                                });
                            }
                        });
                    }
                });
            });

            // Manejo del archivo de audio
            $(document).on('change', '#rutaAudio', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const audio = new Audio();
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        audio.src = e.target.result;
                        audio.addEventListener('loadedmetadata', function() {
                            const duracionEnSegundos = Math.round(audio.duration);
                            const minutos = Math.floor(duracionEnSegundos / 60);
                            const segundos = duracionEnSegundos % 60;
                            const duracionFormateada = `${minutos}:${segundos.toString().padStart(2, '0')}`;
                            $('#duracion').val(duracionFormateada);
                        });
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>

</html>