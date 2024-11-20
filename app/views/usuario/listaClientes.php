<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - Sistema Audio</title>
    <!-- CSS de DataTables y jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Gestión de Clientes</h2>
        <button type="button" id="btnNuevoCliente" class="btn btn-success"> Agregar <i class="bi bi-box-arrow-in-up-right"></i> </button>
        <br>
        <br>
        <div class="contenedorTabla table-responsive">
            <table id="clientesTable" class="display table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Telf</th>
                        <th>Email</th>
                        <th>Detalle</th>
                        <th>Imprimir</th>
                        <th>Acción</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal para Editar Cliente -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        <input type="hidden" id="editarId">
                        <div class="mb-3">
                            <label for="editarNombre" class="form-label">Nombre</label>
                            <input type="text" id="editarNombre" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editarTelefono" class="form-label">Teléfono</label>
                            <input type="text" id="editarTelefono" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editarEmail" class="form-label">Email</label>
                            <input type="email" id="editarEmail" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editarDireccion" class="form-label">Dirección</label>
                            <input type="text" id="editarDireccion" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editarDni" class="form-label">DNI</label>
                            <input type="text" id="editarDni" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editarPassword" class="form-label">Crea nueva contraseña</label>
                            <input type="password" id="editarPassword" class="form-control">
                        </div>
                        <button type="button" id="btnActualizar" class="btn btn-success">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            const table = $('#clientesTable').DataTable({
                "ajax": "crudClientes.php?action=llamarLista", // Ruta ajustada
                "columns": [{
                        "data": "id_cliente"
                    },
                    {
                        "data": "nombre_cliente"
                    },
                    {
                        "data": "telefono"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                            <a href="detalle.php?id=${row.id_cliente}" 
                                class="btn btn-info btn-sm">
                                <i class="bi bi-list-check"></i>
                            </a>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                            <a href="imprimir.php?id=${row.id_cliente}" 
                                class="btn btn-dark btn-sm">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </a>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                                <button 
                                    class="btn btn-warning btn-sm editar" 
                                    data-id="${row.id_cliente}" 
                                    data-nombre="${row.nombre_cliente}" 
                                    data-telefono="${row.telefono}" 
                                    data-email="${row.email}" 
                                    data-direccion="${row.direccion}" 
                                    data-dni="${row.dni}"
                                    data-password="${row.password}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button 
                                    class="btn btn-danger btn-sm eliminar" 
                                    data-id="${row.id_cliente}" 
                                    data-nombre="${row.nombre_cliente}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            `;
                        }
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json"
                }
            });

            // Eliminar cliente
            $('#clientesTable').on('click', '.eliminar', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');

                // Confirmar eliminación
                if (confirm(`¿Está seguro de eliminar al cliente ${nombre}?`)) {
                    $.post('crudClientes.php?action=eliminarCliente', {
                        id
                    }, function(response) {
                        // Manejar la respuesta del servidor
                        const result = JSON.parse(response);
                        if (result.success) {
                            // Si la eliminación fue exitosa, recargar la tabla
                            table.ajax.reload();
                        } else {
                            // Si hubo un error, mostrar un mensaje de error
                            alert(result.error || "Ocurrió un error al eliminar al cliente.");
                        }
                    });
                }
            });


            // Editar cliente
            $('#clientesTable').on('click', '.editar', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                const telefono = $(this).data('telefono');
                const email = $(this).data('email');
                const direccion = $(this).data('direccion');
                const dni = $(this).data('dni');
                const password = "";

                // Rellenar los campos del modal
                $('#editarId').val(id);
                $('#editarNombre').val(nombre);
                $('#editarTelefono').val(telefono);
                $('#editarEmail').val(email);
                $('#editarDireccion').val(direccion);
                $('#editarDni').val(dni);
                $('#editarPassword').val(password);

                // Mostrar el modal
                $('#modalEditar').modal('show');
                // limpiamos el id para poder agregar
            });


            // Actualizar o Agregar cliente
            $('#btnActualizar').click(function() {
                const id = $('#editarId').val();
                const nombre = $('#editarNombre').val();
                const telefono = $('#editarTelefono').val();
                const email = $('#editarEmail').val();
                const direccion = $('#editarDireccion').val();
                const dni = $('#editarDni').val();
                const password = $('#editarPassword').val();

                const action = id == 0 ? 'crearCliente' : 'actualizarCliente'; // Determina la acción según el id

                // Datos del cliente
                const datosCliente = {
                    nombre,
                    telefono,
                    email,
                    direccion,
                    dni,
                    password
                };

                // Si es un id 0, agregamos el id al objeto para crear un cliente
                if (id != 0) {
                    datosCliente.id = id;
                }

                // Llamada al servidor
                $.post(`crudClientes.php?action=${action}`, datosCliente, function() {
                    $('#modalEditar').modal('hide');
                    table.ajax.reload();
                });
            });


            // Agregar cliente
            $('#btnNuevoCliente').click(function() {
                limpiarDatos();
                //Para nuevos usuarios definiremos id = 0
                const id = 0;
                $('#editarId').val(id);
                // Mostrar el modal
                $('#modalEditar').modal('show');
            });


            function limpiarDatos() {
                $('#editarId').val("");
                $('#editarNombre').val("");
                $('#editarTelefono').val("");
                $('#editarEmail').val("");
                $('#editarDireccion').val("");
                $('#editarDni').val("");
                $('#editarPassword').val("");
            }

        });
    </script>

</body>

</html>