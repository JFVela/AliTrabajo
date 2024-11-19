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
                            <label for="editarPassword" class="form-label">Password</label>
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
                "ajax": "getClientes.php?action=llamarLista", // Ruta ajustada
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
                            return `<a href="detalle.php?id=${row.id_cliente}" class="btn btn-info btn-sm"><i class="bi bi-list-check"></i></a>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `<a href="imprimir.php?id=${row.id_cliente}" class="btn btn-primary btn-sm"><i class="bi bi-file-earmark-pdf-fill"></i></a>`;
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
                                    Editar
                                </button>
                                <button class="btn btn-danger btn-sm eliminar" data-id="${row.id_cliente}" data-nombre="${row.nombre_cliente}">Eliminar</button>
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
                if (confirm(`¿Está seguro de eliminar al cliente ${nombre}?`)) {
                    $.post('getClientes.php?action=eliminarCliente', {
                        id
                    }, function() { // Ruta ajustada
                        table.ajax.reload();
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
                const password = $(this).data('password');

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
            });


            // Actualizar cliente
            $('#btnActualizar').click(function() {
                const id = $('#editarId').val();
                const nombre = $('#editarNombre').val();
                const telefono = $('#editarTelefono').val();
                const email = $('#editarEmail').val();
                const direccion = $('#editarDireccion').val();
                const dni = $('#editarDni').val();
                const password = $('#editarPassword').val();

                $.post('getClientes.php?action=actualizarCliente', {
                    id,
                    nombre,
                    telefono,
                    email,
                    direccion,
                    dni,
                    password
                }, function() {
                    $('#modalEditar').modal('hide');
                    table.ajax.reload();
                });
            });


        });
    </script>


</body>

</html>