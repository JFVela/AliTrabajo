<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones</title>
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
    <?php include '../../../includes/headerAdmin.php'; ?>

    <!-- Tabla en html -->
    <div class="container gestion-container">
        <h2 class="gestion-titulo">Cotizaciones</h2>
        <div class="table-responsive">
            <table id="cotizacionTable" class="gestion-tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
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

    <script>
        // Tabla Ajax
        $(document).ready(function() {
            // Inicializar DataTable
            const table = $('#cotizacionTable').DataTable({
                "ajax": "crudCotizaciones.php?action=listarCotizacion", // Ruta para obtener las cotizaciones
                "columns": [{
                        "data": "id_cotizacion"
                    }, // ID de la cotización
                    {
                        "data": "nombre_cliente"
                    }, // Nombre del cliente
                    {
                        "data": "fecha_cotizacion"
                    }, // Fecha de la cotización
                    {
                        "data": "total", // Total de la cotización 
                        "render": function(data) {
                            return `S/. ${parseFloat(data).toFixed(2)}`; // Formato de moneda
                        }
                    },
                    {
                        "data": null, // Detalle
                        "render": function(data, type, row) {
                            return `<a href="detalleCotizacion.php?id=${row.id_cotizacion}" 
                                        class="btn btn-info btn-sm">
                                        <i class="bi bi-list-check"></i>
                                    </a>`;
                        }
                    },
                    {
                        "data": null, // Imprimir
                        "render": function(data, type, row) {
                            return `
                    <a href="../fpdp/imprimir.php?accion=imprimirCotizacion&id=${row.id_cotizacion}" target="_blank" class="btn btn-dark btn-sm">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </a>`;
                        }
                    },
                    {
                        "data": null, // Acción (Eliminar)
                        "render": function(data, type, row) {
                            return `
                    <button class="btn btn-danger btn-sm eliminar" 
                        data-id="${row.id_cotizacion}" 
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

            //sweet para eliminar
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success mx-2",
                    cancelButton: "btn btn-danger mx-2"
                },
                buttonsStyling: false
            });

            //Eliminar cotizacion
            $('#cotizacionTable').on('click', '.eliminar', function() {
                const id = $(this).data('id'); // ID de la cotización
                const nombreCliente = $(this).data('nombre'); // Nombre del cliente asociado a la cotización

                // Confirmar eliminación con SweetAlert
                swalWithBootstrapButtons.fire({
                    title: `¿Está seguro de eliminar la cotización de ${nombreCliente}?`,
                    text: "Esta acción no se puede revertir.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Llamada AJAX para verificar si hay dependencias
                        $.post('crudCotizaciones.php?action=eliminarCotizacion', {
                            id: id
                        }, function(response) {
                            const result = JSON.parse(response);

                            if (result.success === false) {
                                // Si la cotización tiene dependencias, preguntamos si eliminar los detalles
                                swalWithBootstrapButtons.fire({
                                    title: `La cotización de ${nombreCliente} tiene otros registros. ¿Desea eliminarlos también?`,
                                    text: "Esta acción eliminará los tanto detalles como reservas asociados a la cotización.",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonText: "Sí, eliminar todo",
                                    cancelButtonText: "Cancelar",
                                    reverseButtons: true
                                }).then((confirm) => {
                                    if (confirm.isConfirmed) {
                                        // Eliminar cotización y detalles dependientes
                                        $.post('crudCotizaciones.php?action=eliminarOtrosRegistros', {
                                            id: id
                                        }, function(response) {
                                            const result = JSON.parse(response);
                                            if (result.success) {
                                                swalWithBootstrapButtons.fire({
                                                    title: "¡Eliminado!",
                                                    text: `La cotización de ${nombreCliente} y sus otros registros han sido eliminados con éxito.`,
                                                    icon: "success"
                                                });
                                                table.ajax.reload(); // Recargar la tabla
                                            } else {
                                                swalWithBootstrapButtons.fire({
                                                    title: "Error",
                                                    text: result.error,
                                                    icon: "error"
                                                });
                                            }
                                        });
                                    }
                                });
                            } else if (result.success) {
                                swalWithBootstrapButtons.fire({
                                    title: "¡Eliminado!",
                                    text: `La cotización de ${nombreCliente} ha sido eliminada con éxito.`,
                                    icon: "success"
                                });
                                table.ajax.reload(); // Recargar la tabla
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error al eliminar",
                                    text: result.error
                                });
                            }
                        }).fail(function(jqXHR) {
                            Swal.fire({
                                icon: "error",
                                title: "Error del servidor",
                                text: `No se pudo completar la operación. Código de error: ${jqXHR.status}`
                            });
                        });
                    } else {
                        // Cancelación de la acción
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La eliminación ha sido cancelada.",
                            icon: "error"
                        });
                    }
                });
            });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>