$(document).ready(function () {
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
            "render": function (data, type, row) {
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
    $('#serviciosId').on('click', '.btnAgregar', function () {
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
    $('#resumenProductos').on('click', '.btnQuitar', function () {
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
    $('#formReservas').submit(function (event) {
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
            success: function (response) {
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
            error: function () {
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
    duracionInput.addEventListener('input', function () {
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
