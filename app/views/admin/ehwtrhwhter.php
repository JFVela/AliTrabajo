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

<body>
    <form id="formReservas" enctype="multipart/form-data">
        <div class="row">
            <!-- Columna 1: Usuarios -->
            <div class="col-md-4">
                <h6 class="text-center">Usuarios</h6>
                <div id="usuariosInputs">
                    <!-- Nombre de Usuario (Select) -->
                    <div class="mb-3">
                        <label for="nombreUsuario" class="form-label gestion-form-label">Nombre de Usuario</label>
                        <select id="nombreUsuario" class="form-control gestion-form-input" required>
                            <option value="" disabled selected>Seleccione un usuario</option>
                        </select>
                    </div>

                    <!-- Servicios -->
                    <div class="mb-3">
                        <label class="form-label gestion-form-label">Servicios</label>
                        <table id="serviciosId" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Servicio</th>
                                    <th>Categoria</th>
                                    <th>Costo/hr</th>
                                    <th>Accion (agregar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se insertarán las filas de datos dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna 2: Resumen -->
            <div class="col-md-4">
                <h6 class="text-center">Resumen</h6>
                <div id="resumenInputs">
                    <!-- Resumen de los productos seleccionados -->
                    <div class="mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Costo</th>
                                    <th>Subtotal</th>
                                    <th>Accion (quitar)</th>
                                </tr>
                            </thead>
                            <tbody id="resumenProductos">
                                <!-- Aquí se generarán los productos seleccionados por el usuario -->
                            </tbody>
                        </table>
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
                            <input type="number" id="duracion" class="form-control gestion-form-input" required>
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
                            <input type="date" id="fecha" class="form-control gestion-form-input" required>
                        </div>

                        <!-- Hora -->
                        <div class="col-md-6">
                            <label for="hora" class="form-label gestion-form-label">Hora</label>
                            <input type="number" id="hora" class="form-control gestion-form-input" required min="1" max="12">
                            <div>
                                <input type="radio" id="am" name="hora" value="am" required>
                                <label for="am">AM</label>
                                <input type="radio" id="pm" name="hora" value="pm" required>
                                <label for="pm">PM</label>
                            </div>
                        </div>
                    </div>

                    <!-- Distrito -->
                    <div class="mb-3">
                        <label for="distrito" class="form-label gestion-form-label">Distrito</label>
                        <input type="text" id="distrito" class="form-control gestion-form-input" required>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="direccion" class="form-label gestion-form-label">Dirección</label>
                        <input type="text" id="direccion" class="form-control gestion-form-input" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="telefono" class="form-label gestion-form-label">Teléfono</label>
                        <input type="text" id="telefono" class="form-control gestion-form-input" required>
                    </div>

                    <!-- Método de pago -->
                    <div class="mb-3">
                        <label class="form-label gestion-form-label">Método de pago</label>
                        <div>
                            <input type="radio" id="banco" name="pago" value="banco" required>
                            <label for="banco">Banco</label>
                            <input type="radio" id="plin" name="pago" value="plin" required>
                            <label for="plin">Plin</label>
                            <input type="radio" id="yape" name="pago" value="yape" required>
                            <label for="yape">Yape</label>
                        </div>
                    </div>

                    <!-- Subir imagen -->
                    <div class="mb-3">
                        <label for="imagenPago" class="form-label gestion-form-label">Subir Imagen</label>
                        <input type="file" id="imagenPago" class="form-control gestion-form-input" accept="image/*">
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

    <!-- Para el Select de buscar nombre -->
    <script>
        //Para el select del nombre de usuario el cual funciona como buscador
        $('#nombreUsuario').select2({
            placeholder: "Seleccione un usuario",
            allowClear: true,
            ajax: {
                url: 'crudReservas.php?action=listarUsuarios',
                dataType: 'json',
                method: 'GET',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
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

        // Evento para capturar la selección y mostrar el id en consola
        $('#nombreUsuario').on('select2:select', function(e) {
            var selectedId = e.params.data.id; // ID del cliente seleccionado
            console.log('Cliente seleccionado ID:', selectedId);
        });
    </script>

    <script>
        $(document).ready(function() {
            // Array para almacenar los productos seleccionados
            let productosSeleccionados = [];

            // Cargar dinámicamente los productos desde el servidor
            function cargarProductos() {
                $.ajax({
                    url: 'crudProductos.php?action=listarProductos',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const $tablaServicios = $('#serviciosId tbody');
                        $tablaServicios.empty(); // Limpiar la tabla antes de cargar los datos
                        response.data.forEach(producto => {
                            $tablaServicios.append(`
                        <tr data-id="${producto.id_producto}">
                            <td>${producto.id_producto}</td>
                            <td>${producto.nombre}</td>
                            <td>${producto.nombre_categoria}</td>
                            <td>${producto.precio_hr}</td>
                            <td>
                                <input type="number" class="form-control cantidadInput" min="1" max="10" value="1" style="width: 80px;">
                                <button type="button" class="btn btn-success btnAgregar">Agregar</button>
                            </td>
                        </tr>
                    `);
                        });
                    },
                    error: function(xhr) {
                        console.error('Error al cargar los productos:', xhr);
                    }
                });
            }

            // Actualizar la tabla de resumen
            function actualizarResumen() {
                const $resumenProductos = $('#resumenProductos');
                $resumenProductos.empty();

                let total = 0;

                productosSeleccionados.forEach(producto => {
                    const subtotal = producto.cantidad * producto.precio;
                    total += subtotal;

                    $resumenProductos.append(`
                <tr data-id="${producto.id}">
                    <td>${producto.nombre}</td>
                    <td>${producto.cantidad}</td>
                    <td>${producto.precio}</td>
                    <td>${subtotal.toFixed(2)}</td>
                    <td><button type="button" class="btn btn-danger btnQuitar">Quitar</button></td>
                </tr>`);
                });

                // Actualizar el total en el input totalHora
                $('#totalHora').val(`$${total.toFixed(2)}`);
                imprimir()
            }

            // Evento para agregar un producto a la tabla resumen
            $('#serviciosId').on('click', '.btnAgregar', function() {
                const $fila = $(this).closest('tr');
                const id = $fila.data('id');
                const nombre = $fila.find('td').eq(1).text();
                const precio = parseFloat($fila.find('td').eq(3).text());
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
            });

            // Evento para quitar un producto de la tabla resumen
            $('#resumenProductos').on('click', '.btnQuitar', function() {
                const $fila = $(this).closest('tr');
                const id = $fila.data('id');

                // Eliminar el producto del array
                productosSeleccionados = productosSeleccionados.filter(producto => producto.id !== id);

                actualizarResumen();
            });

            function imprimir() {
                console.log(productosSeleccionados);
            }

            // Cargar los productos al iniciar
            cargarProductos();
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>