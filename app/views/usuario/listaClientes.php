<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - Sistema Audio</title>
    <!-- CSS de DataTables y jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primario: #4335A7;
            --color-secundario: #80C4E9;
            --color-fondo: #F3F3E0;
            --color-acento: #133E87;
            --color-texto: #333333;
        }

        body {
            background-color: var(--color-fondo);
            color: var(--color-texto);
            font-family: 'Arial', sans-serif;
        }

        .gestion-clientes-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        .gestion-clientes-titulo {
            color: var(--color-primario);
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .gestion-clientes-boton-agregar {
            background-color: var(--color-acento);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .gestion-clientes-boton-agregar:hover {
            background-color: var(--color-primario);
        }

        .gestion-clientes-tabla {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .gestion-clientes-tabla th,
        .gestion-clientes-tabla td {
            border: none;
            padding: 1rem;
            text-align: left;
        }

        .gestion-clientes-tabla thead {
            background-color: var(--color-secundario);
            color: var(--color-acento);
        }

        .gestion-clientes-tabla tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .gestion-clientes-tabla tbody tr:hover {
            background-color: #e9ecef;
        }

        .gestion-clientes-modal-header {
            background-color: var(--color-primario);
            color: white;
            border-bottom: none;
        }

        .gestion-clientes-modal-titulo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .gestion-clientes-form-label {
            color: var(--color-acento);
            font-weight: bold;
        }

        .gestion-clientes-form-input {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 0.5rem;
        }

        .gestion-clientes-form-input:focus {
            border-color: var(--color-secundario);
            box-shadow: 0 0 0 0.2rem rgba(128, 196, 233, 0.25);
        }

        .gestion-clientes-boton-modal {
            width: 100%;
            background-color: var(--color-acento);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .gestion-clientes-boton-modal:hover {
            background-color: var(--color-primario);
        }
    </style>
</head>

<body>

    <div class="container gestion-clientes-container">
        <h2 class="gestion-clientes-titulo">Gestión de Clientes</h2>
        <button type="button" id="btnNuevoCliente" class="gestion-clientes-boton-agregar">
            Agregar <i class="bi bi-box-arrow-in-up-right"></i>
        </button>
        <br>
        <br>
        <div class="table-responsive">
            <table id="clientesTable" class="gestion-clientes-tabla">
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
                <tbody>
                    <!-- Aquí se insertarán las filas de datos dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Editar Cliente -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header gestion-clientes-modal-header">
                    <h5 class="modal-title gestion-clientes-modal-titulo"><span id="tituloModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        <input type="hidden" id="editarId">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="editarNombre" class="form-label gestion-clientes-form-label">Nombre</label>
                            <input type="text" id="editarNombre" class="form-control gestion-clientes-form-input" required
                                pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                title="El nombre no puede contener números ni caracteres especiales"
                                maxlength="100">
                            <small class="form-text text-muted">
                                Máximo 100 caracteres.
                                <span id="nombreCounter">0/100</span>
                            </small>
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-3">
                            <label for="editarTelefono" class="form-label gestion-clientes-form-label">Teléfono</label>
                            <input type="text" id="editarTelefono" class="form-control gestion-clientes-form-input" required
                                pattern="^9\d{8}$"
                                title="El teléfono debe comenzar con 9 y tener 9 dígitos">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="editarEmail" class="form-label gestion-clientes-form-label">Email</label>
                            <input type="email" id="editarEmail" class="form-control gestion-clientes-form-input" required>
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="editarDireccion" class="form-label gestion-clientes-form-label">Dirección</label>
                            <input type="text" id="editarDireccion" class="form-control gestion-clientes-form-input" required
                                maxlength="255">
                            <small class="form-text text-muted">
                                Máximo 255 caracteres.
                                <span id="direccionCounter">0/255</span>
                            </small>
                        </div>

                        <!-- DNI -->
                        <div class="mb-3">
                            <label for="editarDni" class="form-label gestion-clientes-form-label">DNI</label>
                            <input type="text" id="editarDni" class="form-control gestion-clientes-form-input" required
                                pattern="^\d{8}$"
                                title="El DNI debe ser un número de 8 dígitos">
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-3 position-relative">
                            <label for="editarPassword" class="form-label gestion-clientes-form-label">Crea nueva contraseña</label>
                            <div class="input-group">
                                <input type="password" id="editarPassword" class="form-control gestion-clientes-form-input">
                                <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Botón Actualizar -->
                        <button type="submit" class="gestion-clientes-boton-modal"><span id="textoDinamico"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script>
        //Variables globales
        let textoModal = document.getElementById("textoDinamico");
        let tituloModal = document.getElementById("tituloModal");

        // Contador de caracteres para el campo "Nombre"
        $('#editarNombre').on('input', function() {
            const length = $(this).val().length;
            $('#nombreCounter').text(`${length}/100`);
        });

        // Contador de caracteres para el campo "Dirección"
        $('#editarDireccion').on('input', function() {
            const length = $(this).val().length;
            $('#direccionCounter').text(`${length}/255`);
        });

        // Restablecer contadores cuando se cierra o abre el modal
        $('#modalEditar').on('hidden.bs.modal shown.bs.modal', function() {
            $('#nombreCounter').text('0/100'); // Restablecer contador del nombre
            $('#direccionCounter').text('0/255'); // Restablecer contador de la dirección

        });

        // Mostrar/ocultar contraseña
        $('#togglePassword').click(function() {
            const passwordInput = $('#editarPassword');
            const isPassword = passwordInput.attr('type') === 'password';
            passwordInput.attr('type', isPassword ? 'text' : 'password');
            $(this).html(`<i class="fas fa-${isPassword ? 'eye-slash' : 'eye'}"></i>`);
        });

        //Tabla Ajax
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
                            <a href="detalleCliente.php?id=${row.id_cliente}" 
                                class="btn btn-info btn-sm">
                                <i class="bi bi-list-check"></i>
                            </a>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                            <a href="imprimir.php?accion=imprimirCliente&id=${row.id_cliente}" target="_blank" 
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


            // Inicializar SweetAlert con botones personalizados
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success mx-2",
                    cancelButton: "btn btn-danger mx-2"
                },
                buttonsStyling: false
            });

            // Eliminar cliente
            $('#clientesTable').on('click', '.eliminar', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');

                // Confirmar eliminación
                swalWithBootstrapButtons.fire({
                    title: `¿Está seguro de eliminar al cliente ${nombre}?`,
                    text: "Esta acción no se puede revertir.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Llamada AJAX para eliminar al cliente
                        $.post('crudClientes.php?action=eliminarCliente', {
                            id
                        }, function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                // Mostrar mensaje de éxito
                                swalWithBootstrapButtons.fire({
                                    title: "¡Eliminado!",
                                    text: `El cliente ${nombre} ha sido eliminado con éxito.`,
                                    icon: "success"
                                });
                                table.ajax.reload(); // Recargar la tabla
                            } else {
                                // Mostrar mensaje de error con el código o descripción del error
                                Swal.fire({
                                    icon: "error",
                                    title: "Error al eliminar",
                                    text: result.error
                                });
                            }
                        }).fail(function(jqXHR) {
                            // Manejar errores de la llamada AJAX
                            Swal.fire({
                                icon: "error",
                                title: "Error del servidor",
                                text: `No se pudo completar la operación. Código de error: ${jqXHR.status}`
                            });
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Cancelación de la acción
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La eliminación ha sido cancelada.",
                            icon: "error"
                        });
                    }
                });
            });

            // Editar cliente
            $('#clientesTable').on('click', '.editar', function() {
                tituloModal.textContent = "Editar Cliente";
                textoModal.textContent = "Editar";
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
            $('#formEditar').submit(function(event) {
                // Prevenir el comportamiento predeterminado del formulario
                event.preventDefault();

                // Validar formulario
                if (!this.checkValidity()) {
                    return; // Si el formulario no es válido, no continúa
                }

                // Obtener valores del formulario
                const id = $('#editarId').val();
                const nombre = $('#editarNombre').val();
                const telefono = $('#editarTelefono').val();
                const email = $('#editarEmail').val();
                const direccion = $('#editarDireccion').val();
                const dni = $('#editarDni').val();
                const password = $('#editarPassword').val();

                // Validar contraseña solo si es un nuevo usuario
                if (id == 0 && (!password || password.trim().length < 8)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Debe ingresar una contraseña de al menos 8 caracteres para un nuevo usuario.',
                        confirmButtonText: 'Aceptar'
                    });
                    return; // Detiene la ejecución si no hay contraseña válida
                }

                // Determinar acción según el ID
                const action = id == 0 ? 'crearCliente' : 'actualizarCliente';

                // Crear objeto de datos
                const datosCliente = {
                    nombre,
                    telefono,
                    email,
                    direccion,
                    dni,
                    password
                };

                // Si no es un nuevo cliente, incluir el ID
                if (id != 0) {
                    datosCliente.id = id;
                }

                // Enviar datos al servidor
                $.post(`crudClientes.php?action=${action}`, datosCliente, function(response) {
                    // Manejar la respuesta del servidor
                    const result = JSON.parse(response);

                    if (result.success) {
                        // Ocultar el modal y recargar la tabla primero
                        $('#modalEditar').modal('hide');
                        table.ajax.reload();
                        // Mostrar mensaje de éxito después
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Operación realizada correctamente.',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.error,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                }).fail(function() {
                    // Manejo de errores en la solicitud AJAX
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la comunicación con el servidor.',
                        confirmButtonText: 'Aceptar'
                    });
                });
            });

            // Limpiar datos para nuevo cliente
            $('#btnNuevoCliente').click(function() {
                tituloModal.textContent = "Nuevo Cliente";
                textoModal.textContent = "Agregar Cliente";
                limpiarDatos();
                $('#editarId').val(0);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>

</html>