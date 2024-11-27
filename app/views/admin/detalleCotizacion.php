<?php
// Obtener el ID de la cotización desde la URL
$idCotizacion = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Si no hay un ID válido, redirigir o mostrar un error
if ($idCotizacion <= 0) {
    die("ID de cotización inválido.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cotización</title>
    <!-- CSS de DataTables y jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/admin/estilos.css">
</head>

<body>
    <!-- Tabla en html -->
    <div class="container gestion-container">
        <h2 class="gestion-titulo">Detalle de cotización</h2>
        <button type="button" id="btnNuevoServicio" class="gestion-boton-agregar">
            Agregar <i class="bi bi-box-arrow-in-up-right"></i>
        </button>
        <br>
        <br>
        <div class="table-responsive">
            <table id="detalleCotizacionTable" class="gestion-tabla">
                <thead>
                    <tr>
                        <th>Id-Det</th>
                        <th>Descripcion</th>
                        <th>Proveedor</th>
                        <th>Categoria</th>
                        <th>Foto</th>
                        <th>Prec.</th>
                        <th>Cant.</th>
                        <th>Hr de Alquiler</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se insertarán las filas de datos dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Editar CATEGORIAS -->
    <div class="modal fade" id="modalEditarDetalleCotizacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header gestion-modal-header">
                    <h5 class="modal-title gestion-modal-titulo"><span id="tituloModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formularioEditarDetalleCotizacion" class="row">
                        <input type="hidden" id="editarId">

                        <!-- Primera columna -->
                        <div class="col-md-6">
                            <!-- Nombre del producto -->
                            <div class="mb-3">
                                <label for="editarNombreProducto" class="form-label gestion-form-label">Nombre del producto</label>
                                <input type="text" id="editarNombreProducto" class="form-control gestion-form-input" disabled>
                            </div>

                            <!-- Proveedor -->
                            <div class="mb-3">
                                <label for="editarProveedor" class="form-label gestion-form-label">Proveedor</label>
                                <input type="text" id="editarProveedor" class="form-control gestion-form-input" disabled>
                            </div>

                            <!-- Categoría -->
                            <div class="mb-3">
                                <label for="editarCategoria" class="form-label gestion-form-label">Categoría</label>
                                <input type="text" id="editarCategoria" class="form-control gestion-form-input" disabled>
                            </div>

                            <!-- Foto -->
                            <div class="mb-3 d-flex flex-column align-items-center">
                                <label for="editarFoto" class="form-label gestion-form-label">Foto</label>
                                <img id="editarFoto" class="img-fluid border" alt="Foto del producto" style="height: 100%; max-height: 200px;">
                            </div>
                        </div>

                        <!-- Segunda columna -->
                        <div class="col-md-6">
                            <!-- Precio -->
                            <div class="mb-3">
                                <label for="editarPrecio" class="form-label gestion-form-label">Precio</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" id="editarPrecio" class="form-control gestion-form-input" disabled>
                                </div>
                            </div>

                            <!-- Cantidad y Horas en la misma fila -->
                            <div class="row mb-3">
                                <!-- Cantidad -->
                                <div class="col-md-6">
                                    <label for="editarCantidad" class="form-label gestion-form-label">Cantidad</label>
                                    <div class="input-group">
                                        <input type="number" id="editarCantidad" class="form-control gestion-form-input"
                                            placeholder="1-10 unidades" min="1" max="10" required>
                                        <span class="input-group-text">Unidades</span>
                                    </div>
                                </div>

                                <!-- Horas de alquiler -->
                                <div class="col-md-6">
                                    <label for="editarHoras" class="form-label gestion-form-label">Horas de alquiler</label>
                                    <div class="input-group">
                                        <input type="number" id="editarHoras" class="form-control gestion-form-input"
                                            placeholder="1-12 horas" min="1" max="12" required>
                                        <span class="input-group-text">Horas</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="mb-3">
                                <label for="editarSubtotal" class="form-label gestion-form-label">Subtotal</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" id="editarSubtotal" class="form-control gestion-form-input" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Actualizar -->
                        <div class="col-12">
                            <button type="submit" class="gestion-boton-modal">
                                <span id="textoDinamico"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar más servicios a la cotización -->
    <div class="modal fade" id="modalAgregarServicios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content contenido__modal">
                <div class="modal-header modal__cabecera cabecera__modal">
                    <h5 class="modal-title modal__titulo"><span id="tituloModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formulariServiciosNuevos" enctype="multipart/form-data">
                        <!-- Input oculto para el número de cotización -->
                        <input type="hidden" id="numeroDeCotizacion" name="numeroDeCotizacion" value="<?= $idCotizacion ?>">

                        <div class="row">
                            <!-- Columna 1: Servicios -->
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <!-- Duración -->
                                    <div class="col-md-6">
                                        <label for="agregarDuracion" class="form-label label__formulario">Duración</label>
                                        <input type="number" id="agregarDuracion" min="1" max="12" value=""
                                            class="form-control input__formulario"
                                            required disabled
                                            placeholder="Ingrese duración de Evento">
                                    </div>
                                    <!-- Total Final Hora -->
                                    <div class="col-md-6">
                                        <label for="totalDefinitivo" class="form-label label__formulario">Total Final Hora</label>
                                        <input type="text" id="totalDefinitivo" class="form-control input__formulario" disabled>
                                    </div>
                                </div>
                                <!-- Tabla de servicios -->
                                <div class="mb-3">
                                    <label class="form-label label__formulario">Servicios</label>
                                    <div class="table-responsive">
                                        <table id="tablaServicios" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Servicio</th>
                                                    <th>Categoría</th>
                                                    <th>Costo/hr</th>
                                                    <th>Acción</th>
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
                            <div class="col-md-6 d-flex flex-column align-items-center">
                                <!-- Resumen de los productos seleccionados -->
                                <div class="mb-3 w-100">
                                    <label class="form-label label__formulario">Resumen de servicios seleccionados</label>
                                    <div class="tabla__resumen" style="max-height: 300px; overflow-y: auto;">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Costo</th>
                                                    <th>Subtotal</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="listaServicios">
                                                <!-- Aquí se generarán los productos seleccionados por el usuario -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Total por hora -->
                                <div class="mb-3 w-100">
                                    <label class="form-label label__formulario">Total * Hora</label>
                                    <input type="text" id="totalHora" class="form-control input__formulario" disabled>
                                </div>
                                <!-- Botón de acción -->
                                <div class="mt-4 w-100">
                                    <button type="submit" class="btn btn-primary botonEnviar w-100">
                                        <span id="textoDinamico">Reservar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cantidadInput = document.getElementById("editarCantidad");
            const horasInput = document.getElementById("editarHoras");
            const precioInput = document.getElementById("editarPrecio");
            const subtotalInput = document.getElementById("editarSubtotal");

            function calcularSubtotal() {
                const cantidad = parseInt(cantidadInput.value, 10);
                const horas = parseInt(horasInput.value, 10);
                const precio = parseFloat(precioInput.value);

                if (isNaN(cantidad) || isNaN(horas) || cantidad < 1 || horas < 1) {
                    subtotalInput.value = "No exceda el límite";
                    return;
                }

                if (cantidad > 10 || horas > 12) {
                    subtotalInput.value = "No exceda el límite";
                    return;
                }

                subtotalInput.value = (cantidad * horas * precio).toFixed(2);
            }

            cantidadInput.addEventListener("input", calcularSubtotal);
            horasInput.addEventListener("input", calcularSubtotal);
        });
    </script>

    <script>
        //Variables globales
        let textoModal = document.getElementById("textoDinamico");
        let tituloModal = document.getElementById("tituloModal");
        // Tabla Ajax
        $(document).ready(function() {
            const idCotizacion = <?= $idCotizacion ?>;
            // Inicializar DataTable
            const tableDetalleCotizacion = $('#detalleCotizacionTable').DataTable({
                "ajax": {
                    "url": "crudCotizaciones.php?action=listarDetalles",
                    "type": "POST",
                    "data": {
                        "id_cotizacion": idCotizacion
                    }
                },
                "columns": [{
                        "data": "id_detalle"
                    }, // Id-Det
                    {
                        "data": "nombre_producto"
                    }, // Descripción (nombre del producto)
                    {
                        "data": "nombre_proveedor"
                    }, // Proveedor
                    {
                        "data": "nombre_categoria"
                    }, // Categoría
                    {
                        "data": "foto", // Foto
                        "render": function(data, type, row) {
                            return `<img src="../../../uploads/productos/${data}" alt="${row.nombre_producto}" 
                            class="gestionImagen img-thumbnail" style="max-width: 100px;">`;
                        }
                    },
                    {
                        "data": "precio_hr"
                    }, // Precio
                    {
                        "data": "cantidad"
                    }, // Cantidad
                    {
                        "data": "horas_alquiler"
                    }, // Horas de Alquiler
                    {
                        "data": "subtotal"
                    }, // Subtotal
                    {
                        "data": null, // Acción
                        "render": function(data, type, row) {
                            return `<button class="btn btn-warning btn-sm editar" 
                                        data-id="${row.id_detalle}" 
                                        data-nombre="${row.nombre_producto}" 
                                        data-proveedor="${row.nombre_proveedor}" 
                                        data-categoria="${row.nombre_categoria}" 
                                        data-precio="${row.precio_hr}" 
                                        data-cantidad="${row.cantidad}" 
                                        data-horas="${row.horas_alquiler}" 
                                        data-foto="${row.foto}"
                                        data-subtotal="${row.subtotal}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm eliminar" 
                                        data-iddetalle="${row.id_detalle}" 
                                        data-idcotizacion="${row.id_cotizacion}" 
                                        data-nombre="${row.nombre_producto}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>`;
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

            // Eliminar detalle de cotización
            $('#detalleCotizacionTable').on('click', '.eliminar', function() {
                const idDetalle = $(this).data('iddetalle');
                const idCotizacion = $(this).data('idcotizacion');
                const nombre = $(this).data('nombre');

                // Confirmar eliminación
                swalWithBootstrapButtons.fire({
                    title: `¿Está seguro de eliminar el servicio de: "${nombre}"?`,
                    text: "Esta acción no se puede revertir.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Llamada AJAX para eliminar el detalle y actualizar el total
                        $.post('crudCotizaciones.php?action=eliminarDetalle', {
                            id_detalle: idDetalle,
                            id_cotizacion: idCotizacion
                        }, function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                // Mostrar mensaje de éxito
                                swalWithBootstrapButtons.fire({
                                    title: "¡Eliminado!",
                                    text: `El servicio "${nombre}" ha sido eliminado con éxito.`,
                                    icon: "success"
                                });
                                tableDetalleCotizacion.ajax.reload(); // Recargar la tabla
                            } else {
                                // Mostrar mensaje de error
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

            // Editar categoría
            $('#detalleCotizacionTable').on('click', '.editar', function() {
                tituloModal.textContent = "Editar Categoría";
                textoModal.textContent = "Editar";

                const idDetalle = $(this).data('id');
                const nombreProducto = $(this).data('nombre');
                const proveedor = $(this).data('proveedor');
                const categoria = $(this).data('categoria');
                const foto = $(this).data('foto');
                const precio = $(this).data('precio');
                const cantidad = $(this).data('cantidad');
                const horas = $(this).data('horas');
                const subtotal = $(this).data('subtotal');

                // Rellenar los campos del modal
                $('#editarId').val(idDetalle);
                $('#editarNombreProducto').val(nombreProducto);
                $('#editarProveedor').val(proveedor);
                $('#editarCategoria').val(categoria);
                $('#editarFoto').attr('src', "../../../uploads/productos/" + foto);
                $('#editarPrecio').val(precio);
                $('#editarCantidad').val(cantidad);
                $('#editarHoras').val(horas);
                $('#editarSubtotal').val(subtotal);

                // Mostrar el modal
                $('#modalEditarDetalleCotizacion').modal('show');
            });

            // Actualizar
            $('#formularioEditarDetalleCotizacion').submit(function(event) {
                event.preventDefault();

                // Obtener valores del formulario
                const idDetalle = $('#editarId').val();
                const cantidad = $('#editarCantidad').val();
                const horas = $('#editarHoras').val();
                const subtotal = $('#editarSubtotal').val();

                // Crear objeto FormData para enviar datos
                const formData = new FormData();
                formData.append('id', idDetalle);
                formData.append('cantidad', cantidad);
                formData.append('horas', horas);
                formData.append('subtotal', subtotal);

                // Enviar datos al servidor
                $.ajax({
                    url: `crudCotizaciones.php?action=actualizarDetalle`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const result = JSON.parse(response);

                        if (result.success) {
                            $('#modalEditarDetalleCotizacion').modal('hide');
                            tableDetalleCotizacion.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Actualización ser servicio exitosa!',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.error,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error en la comunicación con el servidor.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });

            // Limpiar datos para nueva categoría
            $('#btnNuevoServicio').click(function() {
                limpiarDatos()
                tituloModal.textContent = "Nueva Categoría";
                textoModal.textContent = "Agregar Categoría";
                $('#modalAgregarServicios').modal('show');
            });

            function limpiarDatos() {
                $('#editarId').val("");
                $('#editarNombre').val("");
                $('#editarDescripcion').val("");
                $('#fotoExistente').val("");
            }

            let productosSeleccionados = [];

            const tablaAgregarServicios = $('#tablaServicios').DataTable({
                "ajax": "crudProductos.php?action=listarProductos",
                "columns": [{
                        "data": "id_producto"
                    },
                    {
                        "data": "nombre"
                    },
                    {
                        "data": "nombre_categoria"
                    },
                    {
                        "data": "precio_hr"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `<div class="d-grid gap-2">
                    <input type="number" class="form-control cantidadInput" min="1" max="10" value="1" style="width: 80px;">
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
                "lengthChange": false,
                "pageLength": 3
            });

            function actualizar() {
                const $listaServicios = $('#listaServicios');
                $listaServicios.empty();

                let total = 0;

                productosSeleccionados.forEach(producto => {
                    const subtotal = producto.cantidad * producto.precio;
                    total += subtotal;

                    $listaServicios.append(`<tr data-id="${producto.id}">
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

                $('#totalHora').val(total.toFixed(2));
                verificarDuracion(total);
            }

            $('#tablaServicios').on('click', '.btnAgregar', function() {
                const $fila = $(this).closest('tr');
                const id = $(this).data('id');
                const data = tablaAgregarServicios.row($fila).data();
                const nombre = data.nombre;
                const precio = parseFloat(data.precio_hr);
                const cantidad = parseInt($fila.find('.cantidadInput').val()) || 1;

                const productoExistente = productosSeleccionados.find(producto => producto.id === id);

                if (productoExistente) {
                    productoExistente.cantidad = cantidad;
                    productoExistente.precio = precio;
                } else {
                    productosSeleccionados.push({
                        id,
                        nombre,
                        precio,
                        cantidad
                    });
                }

                actualizar();
                calcularNuevoTotal();
            });

            $('#listaServicios').on('click', '.btnQuitar', function() {
                const $fila = $(this).closest('tr');
                const id = $fila.data('id');

                productosSeleccionados = productosSeleccionados.filter(producto => producto.id !== id);

                actualizar();
                calcularNuevoTotal();
            });

            $('#formulariServiciosNuevos').submit(function(event) {
                event.preventDefault();

                let idCotizacion = $('#numeroDeCotizacion').val();
                let horas = $('#agregarDuracion').val();
                let totalDefinitivo = $('#totalDefinitivo').val();
                let totalFinalNumerico = parseFloat(totalDefinitivo);

                if (isNaN(totalFinalNumerico) || totalFinalNumerico <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Agrega algun servicio y elije la duración',
                        confirmButtonText: 'Aceptar',
                    });
                    return;
                }

                const formData = new FormData();
                const productosSeleccionadosJSON = JSON.stringify(productosSeleccionados);
                formData.append('productosSeleccionados', productosSeleccionadosJSON);
                formData.append('totalFinal', totalFinalNumerico);
                formData.append('idCotizacion', idCotizacion);
                formData.append('horas', horas);

                $.ajax({
                    url: `crudCotizaciones.php?action=agregarServiciosDetalle`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            tableDetalleCotizacion.ajax.reload();
                            $('#modalAgregarServicios').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Servicio agregado correctamente.',
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
            });

            function limpiar() {
                productosSeleccionados = [];
                tablaAgregarServicios.ajax.reload();
                $('#listaServicios').empty();
                $('#totalHora').val('0.00');
                $('#agregarDuracion').val('').prop('disabled', true);
                $('#totalDefinitivo').val('0.00');
            }

            function verificarDuracion(totalHora) {
                const duracionInput = $('#agregarDuracion');
                if (totalHora > 0) {
                    duracionInput.prop('disabled', false);
                } else {
                    duracionInput.prop('disabled', true).val('');
                }
            }

            function calcularNuevoTotal() {
                const totalHora = parseFloat($('#totalHora').val()) || 0;
                const duracion = parseInt($('#agregarDuracion').val()) || 1;
                const totalDefinitivo = totalHora * duracion;

                $('#totalDefinitivo').val(totalDefinitivo.toFixed(2));
            }

            $('#agregarDuracion').on('input change', calcularNuevoTotal);

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>

</html>