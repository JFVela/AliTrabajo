//Variables globales
let textoModal = document.getElementById("textoDinamico");
let tituloModal = document.getElementById("tituloModal");

// Tabla Ajax
$(document).ready(function () {
    const tableReserva = $('#reservasTable').DataTable({
        "ajax": "crudReservas.php?action=listarReservas", // Ruta para obtener las reservas
        "columns": [{
            "data": "id_reserva" // ID de reserva
        },
        {
            "data": "nombre_cliente" // Nombre del cliente
        },
        {
            "data": "capturaPago_url", // Comprobante de pago
            "render": function (data, type, row) {
                return `
                <img src="../../../uploads/comprobantes/${data}" alt="${data}" class="gestionImagenComprobante img-thumbnail">`;
            }
        },
        {
            "data": "estadoPago" // Estado del pago
        },
        {
            "data": "direccion_reserva" // Dirección de la reserva
        },
        {
            "data": "fecha_reserva" // Fecha de la reserva
        },
        {
            "data": null, // Hora de la reserva (combinada)
            "render": function (data, type, row) {
                return `${row.hora_reserva} ${row.ampm}`;
            }
        },
        {
            "data": "estado_reserva" // Estado de la reserva
        },
        {
            "data": null, // Detalle
            "render": function (data, type, row) {
                return `
                <a href="detalleReserva.php?id=${row.id_reserva}" class="btn btn-info btn-sm">
                    <i class="bi bi-list-check"></i>
                </a>`;
            }
        },
        {
            "data": null, // Imprimir
            "render": function (data, type, row) {
                return `
                <a href="../fpdp/imprimir.php?accion=imprimirReserva&id=${row.id_reserva}" target="_blank" class="btn btn-dark btn-sm">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                </a>`;
            }
        },
        {
            "data": null, // Acción (editar/eliminar)
            "render": function (data, type, row) {
                return `
                <button class="btn btn-warning btn-sm editar" 
                    data-id="${row.id_reserva}"
                    data-nombre_cliente="${row.nombre_cliente}"
                    data-iddist="${row.idDist}"
                    data-nombre_provincia="${row.nombre_provincia}"
                    data-capturaPago_url="${row.capturaPago_url}"
                    data-fecha_reserva="${row.fecha_reserva}"
                    data-hora_reserva="${row.hora_reserva}"
                    data-ampm="${row.ampm}"
                    data-telefonoContacto="${row.telefonoContacto}"
                    data-estado_reserva="${row.estado_reserva}">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-danger btn-sm eliminar" 
                    data-id="${row.id_reserva}" 
                    data-foto="${row.capturaPago_url}" 
                    data-nombre="${row.nombre_cliente}">
                    <i class="bi bi-trash-fill"></i>
                </button>`;
            }
        }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json"
        }
    });
    // Array para almacenar los productos seleccionados
    let productosSeleccionados = [];

    // Inicializar DataTable de servicios
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

            $resumenProductos.append(`
                    <tr data-id="${producto.id}">
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

    //Enviar los datos de reserva al servidor
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
                    $('#modalFormulario').modal('hide');
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
        tableReserva.ajax.reload();
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
    }

    // Inicializar SweetAlert con botones personalizados
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success mx-2",
            cancelButton: "btn btn-danger mx-2"
        },
        buttonsStyling: false
    });

    // Eliminar reserva
    $('#reservasTable').on('click', '.eliminar', function () {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const foto = $(this).data('foto');

        // Confirmar eliminación con SweetAlert
        swalWithBootstrapButtons.fire({
            title: `¿Está seguro de eliminar la reserva de ${nombre}?`,
            text: "Esta acción no se puede revertir.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Llamada AJAX para eliminar la reserva
                $.post('crudReservas.php?action=eliminarReserva', {
                    id_reserva: id,
                    foto: foto
                }, function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        // Mostrar mensaje de éxito
                        swalWithBootstrapButtons.fire({
                            title: "¡Eliminado!",
                            text: `La reserva de ${nombre} ha sido eliminada con éxito.`,
                            icon: "success"
                        });
                        tableReserva.ajax.reload(); // Recargar la tabla de reservas
                    } else {
                        // Mostrar mensaje de error con el código o descripción del error
                        Swal.fire({
                            icon: "error",
                            title: "Error al eliminar",
                            text: result.error
                        });
                    }
                }).fail(function (jqXHR) {
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

    // Editar Producto
    $('#reservasTable').on('click', '.editar', function () {
        // Obtener los datos de la reserva seleccionada
        
        const idReserva = $(this).data('id');
        const nombreCliente = $(this).data('nombre_cliente');
        const idDistrito = $(this).data('iddist');
        const nombreDistrito = $(this).data('nombre_provincia');
        const capturaPagoUrl = $(this).data('capturapago_url');
        const fechaReserva = $(this).data('fecha_reserva');
        const horaReserva = $(this).data('hora_reserva');
        const ampm = $(this).data('ampm');
        const telefonoContacto = $(this).data('telefonocontacto');
        const estadoReserva = $(this).data('estado_reserva');

        // Rellenar los campos del formulario con los datos de la reserva
        $('#id_Reserva').val(idReserva);
        $('#id_editar_cliente').val(nombreCliente);
        $('#id_editar_distrito').val(idDistrito);
        $('#id_editar_foto').attr('src', capturaPagoUrl);
        $('#id_editar_direccion').val($(this).data('direccion'));
        $('#id_editar_fecha_reserva').val(fechaReserva);
        $('#id_editar_hora_reserva').val(horaReserva);
        $('#id_editar_ampm').val(ampm);
        $('#id_editar_telefono_contacto').val(telefonoContacto);
        $('#id_editar_estado_reserva').val(estadoReserva);

        // Mostrar el modal
        $('#modalEditar').modal('show');
    });

    // Limpiar datos para nueva categoría
    $('#btnAgregarReserva').click(function () {
        tituloModal.textContent = "Nueva Reserva";
        textoModal.textContent = "Agregar Reserva";
        limpiar()
        $('#modalFormulario').modal('show');
    });

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
