<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <!-- CSS de DataTables y jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/admin/estilos.css">
    <!-- Agregar Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Agregar Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>

<body style="background-color: white;">
    <form id="formReservas" enctype="multipart/form-data">
        <div class="row">
            <!-- Columna 1: Usuarios -->
            <div class="col-md-4">
                <h6 class="text-center">Usuarios</h6>
                <div id="usuariosInputs">
                    <!-- Nombre de Usuario (Select) -->
                    <div class="mb-3">
                        <label for="nombreUsuario" class="form-label gestion-form-label">Nombre de Usuario</label>
                        <select id="nombreUsuario" class="form-control gestion-form-input" style="width: 50%;" required>
                            <option value="" disabled selected>Seleccione un usuario</option>
                        </select>
                    </div>
                    <input type="hidden" id="idCliente">

                    <!-- Servicios -->
                    <div class="mb-3">
                        <label class="form-label gestion-form-label">Servicios</label>
                        <div class="table-responsive">
                            <table id="serviciosId" class="gestion-tabla">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Servicio</th>
                                        <th>Categoria</th>
                                        <th>Costo/hr</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se insertarán las filas de datos dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna 2: Resumen -->
            <div class="col-md-4">
                <h6 class="text-center">Resumen</h6>
                <div id="resumenInputs">
                    <!-- Resumen de los productos seleccionados -->
                    <div class="mb-3">
                        <div class="table-responsive contenedorTablaResumen">
                            <table class="gestion-tabla">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Costo</th>
                                        <th>Subtotal</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody id="resumenProductos">
                                    <!-- Aquí se generarán los productos seleccionados por el usuario -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Total por hora -->
                    <div class="mb-3">
                        <label class="form-label gestion-form-label">Total * Hora</label>
                        <input type="text" id="totalHora" class="form-control gestion-form-input" disabled>
                    </div>
                </div>
            </div>

            <!-- Columna 3: Reserva de Título -->
            <div class="col-md-4">
                <h6 class="text-center">Reserva de Título</h6>
                <div id="reservaInputs">
                    <!-- Duración y Total Final Hora en la misma fila -->
                    <div class="mb-3 row">
                        <!-- Duración -->
                        <div class="col-md-6">
                            <label for="duracion" class="form-label gestion-form-label">Duración</label>
                            <input type="number" id="duracion" min="1" max="12" value="1" class="form-control gestion-form-input" required disabled>
                        </div>

                        <!-- Total Final Hora -->
                        <div class="col-md-6">
                            <label for="totalFinalHora" class="form-label gestion-form-label">Total Final Hora</label>
                            <input type="text" id="totalFinalHora" class="form-control gestion-form-input" disabled>
                        </div>
                    </div>

                    <!-- Fecha y Hora en la misma fila -->
                    <div class="mb-3 row">
                        <!-- Fecha -->
                        <div class="col-md-6">
                            <label for="fecha" class="form-label gestion-form-label">Fecha</label>
                            <input type="date" id="fecha" class="form-control gestion-form-input" required min="">
                        </div>

                        <!-- Hora -->
                        <div class="col-md-6">
                            <label for="hora" class="form-label gestion-form-label">Hora</label>
                            <input type="number" id="hora" class="form-control gestion-form-input" required min="1" max="12" value="1">
                            <div>
                                <input type="radio" id="am" name="hora" value="AM" required>
                                <label for="am">AM</label>
                                <input type="radio" id="pm" name="hora" value="PM" required>
                                <label for="pm">PM</label>
                            </div>
                            <input type="hidden" id="horaElejida" value="">
                        </div>
                    </div>

                    <!-- Distrito -->
                    <div class="mb-3">
                        <label for="distrito" class="form-label gestion-form-label">Distrito</label>
                        <select id="distrito" class="form-control gestion-form-input" required>
                            <option value="" disabled selected>Seleccione un distrito</option>
                        </select>
                    </div>
                    <input type="hidden" id="idDistrito">

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="direccion" class="form-label gestion-form-label">Dirección</label>
                        <input type="text" id="direccion" class="form-control gestion-form-input" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="telefono" class="form-label gestion-form-label">Teléfono</label>
                        <input type="text" id="telefono" class="form-control gestion-form-input" required pattern="9[0-9]{8}" title="Debe contener 9 dígitos y comenzar con 9.">
                    </div>

                    <!-- Método de pago -->
                    <div class="mb-3">
                        <label for="metodoPago" class="form-label gestion-form-label">Método de Pago</label>
                        <select id="metodoPago" class="form-control gestion-form-input" required>
                            <option value="" disabled selected>Seleccione un método de pago</option>
                        </select>
                    </div>
                    <input type="hidden" id="idMetodoPago"> <!-- Input oculto para guardar el ID seleccionado -->

                    <!-- Subir imagen -->
                    <div class="mb-3">
                        <label for="imagenPago" class="form-label gestion-form-label">Subir comprobante de pago</label>
                        <input type="file" id="imagenPago" class="form-control gestion-form-input"
                            accept="image/*"
                            title="Seleccione una imagen" required>
                        <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="gestion-boton-modal">
                <span id="textoDinamico">Reservar</span>
            </button>
        </div>
    </form>


    <!-- Validaciones dinamicas -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restringir la fecha al día de hoy en adelante
            const fechaInput = document.getElementById('fecha');
            const hoy = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
            fechaInput.setAttribute('min', hoy);

            // Validar el teléfono
            const telefonoInput = document.getElementById('telefono');
            telefonoInput.addEventListener('input', function() {
                const valor = telefonoInput.value;
                const esValido = /^[9][0-9]{8}$/.test(valor);

                if (!esValido && valor.length > 0) {
                    telefonoInput.setCustomValidity('El teléfono debe tener 9 dígitos y comenzar con 9.');
                } else {
                    telefonoInput.setCustomValidity('');
                }
            });
        });

        //OBTENER EL AM O PM
        document.querySelectorAll('input[name="hora"]').forEach(radio => {
            radio.addEventListener('change', () => {
                // Actualiza el campo con el valor del radio seleccionado
                const horaElegida = document.querySelector('input[name="hora"]:checked').value;
                document.getElementById('horaElejida').value = horaElegida;
            });
        });
    </script>

    <!-- Script para Select de Nombre de Usuario y Distrito -->
    <script>
        $(document).ready(function() {
            // Inicializar el select2 para Nombre de Usuario
            $('#nombreUsuario').select2({
                placeholder: "Seleccione un usuario",
                allowClear: true,
                ajax: {
                    url: 'crudReservas.php?action=listarUsuarios', // URL para obtener usuarios
                    dataType: 'json',
                    method: 'GET',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        }; // Enviar el término de búsqueda
                    },
                    processResults: function(data) {
                        // Transformar la respuesta para Select2
                        return {
                            results: data.data.map(function(cliente) {
                                return {
                                    id: cliente.id_cliente,
                                    text: cliente.nombre_cliente
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Evento: Capturar selección del usuario
            $('#nombreUsuario').on('select2:select', function(e) {
                const selectedId = e.params.data.id;
                $('#idCliente').val(selectedId); // Asignar ID al input oculto
            });

            // Para el select del distrito, con búsqueda dinámica
            $('#distrito').select2({
                placeholder: "Seleccione un distrito",
                allowClear: true,
                ajax: {
                    url: 'crudUbicacion.php?action=listarDistrito', // URL para buscar distritos
                    dataType: 'json',
                    method: 'GET',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // Enviar el término de búsqueda
                        };
                    },
                    processResults: function(data) {
                        // Transformar la respuesta para Select2
                        return {
                            results: data.data.map(function(distrito) {
                                return {
                                    id: distrito.idDist,
                                    text: distrito.Distrito
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Evento para capturar la selección y asignar el ID del distrito
            $('#distrito').on('select2:select', function(e) {
                const selectedId = e.params.data.id;
                $('#idDistrito').val(selectedId); // Asignar ID al input oculto
            });

            // Función adicional para limpiar inputs (si es necesaria)
            function limpiarInput() {
                $('#idCliente').val(''); // Limpia el input del cliente
            }
        });
    </script>
    <!-- Script para select de Metodo de Pago -->
    <script>
        // Cargar los métodos de pago en el select
        function cargarMetodosPago() {
            $.ajax({
                url: 'crudMetodoPago.php?action=listarMetodo', // URL para obtener los métodos de pago
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    const $metodoPagoSelect = $('#metodoPago');
                    $metodoPagoSelect.empty(); // Limpia las opciones actuales
                    $metodoPagoSelect.append('<option value="" disabled selected>Seleccione un método de pago</option>'); // Placeholder

                    response.data.forEach(metodo => {
                        $metodoPagoSelect.append(`
                    <option value="${metodo.idPago}">${metodo.descripcionPago} (${metodo.metodoPago})</option>
                `);
                    });
                },
                error: function(xhr) {
                    console.error('Error al cargar los métodos de pago:', xhr);
                }
            });
        }

        // Evento para capturar la selección y asignar el ID del método de pago
        $('#metodoPago').on('change', function() {
            const idMetodoPago = $(this).val(); // Obtener el ID seleccionado
            $('#idMetodoPago').val(idMetodoPago); // Asignar al input oculto
        });

        // Llamar a la función al cargar la página
        $(document).ready(function() {
            cargarMetodosPago();
        });
    </script>
    <!-- Script para el Modal -->
    <script>
        $(document).ready(function() {
            // Array para almacenar los productos seleccionados
            let productosSeleccionados = [];

            // Inicializar DataTable
            const tablaServicios = $('#serviciosId').DataTable({
                "ajax": "crudProductos.php?action=listarProductos", // Ruta para obtener los productos
                "columns": [{
                        "data": "id_producto"
                    }, // ID del producto
                    {
                        "data": "nombre"
                    }, // Nombre del producto
                    {
                        "data": "nombre_categoria"
                    }, // Categoría del producto
                    {
                        "data": "precio_hr"
                    }, // Precio por hora
                    {
                        "data": null, // Columna para acciones
                        "render": function(data, type, row) {
                            return `
                    <div class="d-grid gap-2">
                        <input type="number" class="form-control cantidadInput" 
                               min="1" max="10" value="1" style="width: 80px;">
                        <button type="button" class="btn btn-success btnAgregar" data-id="${row.id_producto}">
                            <i class="bi bi-plus-circle-fill"></i>
                        </button>
                    </div>`;
                        }
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json"
                },
                "lengthChange": false, // Desactiva la opción "Mostrar X registros"
                "pageLength": 3 // Fija la cantidad de registros por página a 4
            });

            // Actualizar la tabla de resumen
            function actualizarResumen() {
                const $resumenProductos = $('#resumenProductos');
                $resumenProductos.empty();

                let total = 0;

                productosSeleccionados.forEach(producto => {
                    const subtotal = producto.cantidad * producto.precio;
                    total += subtotal;

                    $resumenProductos.append(`<tr data-id="${producto.id}">
                                                    <td>${producto.nombre}</td>
                                                    <td>${producto.cantidad}</td>
                                                    <td>${producto.precio}</td>
                                                    <td>${subtotal.toFixed(2)}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btnQuitar">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </td>
                                                </tr>`);
                });

                // Actualizar el total en el input totalHora
                $('#totalHora').val(`$${total.toFixed(2)}`);
            }

            // Evento para agregar un producto a la tabla resumen
            $('#serviciosId').on('click', '.btnAgregar', function() {
                const $fila = $(this).closest('tr');
                const id = $(this).data('id');
                const data = tablaServicios.row($fila).data(); // Obtener datos del DataTable
                const nombre = data.nombre;
                const precio = parseFloat(data.precio_hr);
                const cantidad = parseInt($fila.find('.cantidadInput').val()) || 1;

                // Verificar si el producto ya existe en el array
                const productoExistente = productosSeleccionados.find(producto => producto.id === id);

                if (productoExistente) {
                    // Reemplazar los datos del producto existente
                    productoExistente.cantidad = cantidad;
                    productoExistente.precio = precio;
                } else {
                    // Agregar un nuevo producto
                    productosSeleccionados.push({
                        id,
                        nombre,
                        precio,
                        cantidad
                    });
                }

                actualizarResumen();
                NuevoTotal();
            });

            // Evento para quitar un producto de la tabla resumen
            $('#resumenProductos').on('click', '.btnQuitar', function() {
                const $fila = $(this).closest('tr');
                const id = $fila.data('id');

                // Eliminar el producto del array
                productosSeleccionados = productosSeleccionados.filter(producto => producto.id !== id);

                actualizarResumen();
                NuevoTotal();
            });

            // Inicializar con un input dinámico para el total
            $('#totalHora').val('$0.00');

            // Actualizar o Agregar Producto
            $('#formReservas').submit(function(event) {
                event.preventDefault();

                let totalFinalHora = $('#totalFinalHora').val();
                totalFinalHora = totalFinalHora.replace('$', '');
                let totalFinalNumerico = parseFloat(totalFinalHora);
                totalFinalNumerico = totalFinalNumerico.toFixed(2);

                // Crear objeto 
                const formData = new FormData();

                // Datos para la tabla `cotizaciones`
                formData.append('idCliente', $('#idCliente').val());
                formData.append('totalFinal', totalFinalNumerico);

                // Datos para la tabla `cotizacion_detalles`
                const productosSeleccionadosJSON = JSON.stringify(productosSeleccionados);
                formData.append('productosSeleccionados', productosSeleccionadosJSON);
                formData.append('horasAlquiler', $('#duracion').val());

                // Datos para la tabla `capturapago`
                const fotoNueva = $('#imagenPago')[0].files[0];
                if (fotoNueva) {
                    formData.append('capturaPago', fotoNueva);
                }
                formData.append('idMetodoPago', $('#idMetodoPago').val());

                // Datos para la tabla `reservas`
                formData.append('idDistrito', $('#idDistrito').val());
                formData.append('direccion', $('#direccion').val());
                formData.append('fechaReserva', $('#fecha').val());
                formData.append('horaReserva', $('#hora').val());
                formData.append('ampm', $('#horaElejida').val());
                formData.append('telefonoContacto', $('#telefono').val());

                //Enviar datos al servidor
                $.ajax({
                    url: `crudReservas.php?action=nuevaReserva`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Reserva creada correctamente.',
                                confirmButtonText: 'Aceptar',
                            });
                            limpiar();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.error,
                                confirmButtonText: 'Aceptar',
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al comunicar con el servidor.',
                            confirmButtonText: 'Aceptar',
                        });
                    },
                });
                limpiar();
            });

            //FUNCION LIMPIAR
            function limpiar() {
                //Limpiar los datos del cliente
                $('#nombreUsuario').html('<option value="" disabled selected>Seleccione un usuario</option>');
                $('#idCliente').val('');
                //Limpiamos el array de productos y todo lo relacionado
                productosSeleccionados = [];
                tablaServicios.ajax.reload();
                $('#resumenProductos').empty();
                //Limpiamos la parte de reservas
                $('#totalHora').val('$0.00');
                $('#duracion').val('');
                $('#totalFinalHora').val('');
                $('#fecha').val('');
                $('#hora').val('');
                $('input[name="hora"]').prop('checked', false);
                $('#horaElejida').val('');
                $('#distrito').html('<option value="" disabled selected>Seleccione un distrito</option>');
                $('#idDistrito').val('');
                $('#direccion').val('');
                $('#telefono').val('');
                $('#metodoPago').val('').prop('selectedIndex', 0); // Restablece al valor inicial
                $('#idMetodoPago').val(''); // Limpia el input oculto
                $('#imagenPago').val('');
                $('#modalEditar').modal('show');
            }
        });

        function NuevoTotal() {
            const duracionInput = document.getElementById('duracion');
            const totalHoraInput = document.getElementById('totalHora');
            const totalFinalHoraInput = document.getElementById('totalFinalHora');

            // Habilitar el campo duracion si totalHora > 0
            function habilitarDuracion() {
                const totalHora = parseFloat(totalHoraInput.value.replace('$', '')) || 0;
                if (totalHora > 0) {
                    duracionInput.removeAttribute('disabled');
                } else {
                    duracionInput.setAttribute('disabled', true);
                    totalFinalHoraInput.value = ''; // Limpiar el total final si está deshabilitado
                }
            }

            // Calcular el nuevo total
            function calcularNuevoTotal() {
                const totalHora = parseFloat(totalHoraInput.value.replace('$', '')) || 0;
                const duracion = parseInt(duracionInput.value) || 1;

                if (duracion > 0) {
                    const nuevoTotal = totalHora * duracion;
                    totalFinalHoraInput.value = `$${nuevoTotal.toFixed(2)}`;
                } else {
                    totalFinalHoraInput.value = ''; // Limpiar el total si la duración no es válida
                }
            }

            // Escuchar cambios en totalHora
            totalHoraInput.addEventListener('input', habilitarDuracion);

            // Escuchar cambios en duracion
            duracionInput.addEventListener('input', calcularNuevoTotal);
            duracionInput.addEventListener('change', calcularNuevoTotal);

            // Validar que la duración sea numérica y positiva
            duracionInput.addEventListener('input', function() {
                const duracion = parseInt(duracionInput.value);
                if (duracion <= 0 || isNaN(duracion)) {
                    duracionInput.setCustomValidity('La duración debe ser un número positivo.');
                } else {
                    duracionInput.setCustomValidity('');
                }
            });

            // Inicializar validaciones
            habilitarDuracion();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>