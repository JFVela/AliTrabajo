<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas</title>
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
        <h2 class="gestion-titulo">Gestión de Productos</h2>
        <button type="button" id="btnAgregarReserva" class="gestion-boton-agregar">
            Agregar <i class="bi bi-box-arrow-in-up-right"></i>
        </button>
        <br>
        <br>
        <div class="table-responsive">
            <table id="reservasTable" class="gestion-tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Cliente</th>
                        <th>Comprobante</th>
                        <th>Estado Pago</th>
                        <th>Direccion-Reserva</th>
                        <th>Fecha-Reserva</th>
                        <th>Hora</th>
                        <th>Estado Reserva</th>
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

    <!-- Modal para Editar Reservas -->
    <div class="modal fade" id="modalFormulario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title gestion-modal-titulo"><span id="tituloModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formReservas" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Columna 1: Usuarios -->
                            <div class="col-md-4">
                                <h6 class="text-center">Usuarios</h6>
                                <div id="usuariosInputs">
                                    <!-- Aquí se colocarán los inputs más adelante -->
                                </div>
                            </div>
                            <!-- Columna 2: Resumen -->
                            <div class="col-md-4">
                                <h6 class="text-center">Resumen</h6>
                                <div id="resumenInputs">
                                    <!-- Aquí se colocarán los inputs más adelante -->
                                </div>
                            </div>
                            <!-- Columna 3: Reserva de Titulo -->
                            <div class="col-md-4">
                                <h6 class="text-center">Reserva de Título</h6>
                                <div id="reservaInputs">
                                    <!-- Aquí se colocarán los inputs más adelante -->
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <!-- Botón para crear producto -->
                            <button type="submit" class="gestion-boton-modal">
                                <span id="textoDinamico"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        //Variables globales
        let textoModal = document.getElementById("textoDinamico");
        let tituloModal = document.getElementById("tituloModal");
        // Tabla Ajax
        $(document).ready(function() {
            const table = $('#reservasTable').DataTable({
                "ajax": "crudReservas.php?action=listarReservas", // Ruta para obtener las reservas
                "columns": [{
                        "data": "id_reserva" // ID de reserva
                    },
                    {
                        "data": "nombre_cliente" // Nombre del cliente
                    },
                    {
                        "data": "capturaPago_url", // Comprobante de pago
                        "render": function(data, type, row) {
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
                        "render": function(data, type, row) {
                            return `${row.hora_reserva} ${row.ampm}`;
                        }
                    },
                    {
                        "data": "estado_reserva" // Estado de la reserva
                    },
                    {
                        "data": null, // Detalle
                        "render": function(data, type, row) {
                            return `
                        <a href="detalleReserva.php?id=${row.id_reserva}" class="btn btn-info btn-sm">
                            <i class="bi bi-list-check"></i>
                        </a>`;
                        }
                    },
                    {
                        "data": null, // Imprimir
                        "render": function(data, type, row) {
                            return `
                        <a href="../fpdp/imprimir.php?accion=imprimirReserva&id=${row.id_reserva}" target="_blank" class="btn btn-dark btn-sm">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </a>`;
                        }
                    },
                    {
                        "data": null, // Acción (editar/eliminar)
                        "render": function(data, type, row) {
                            return `
                        <button class="btn btn-warning btn-sm editar" 
                            data-id="${row.id_reserva}">
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

            // Inicializar SweetAlert con botones personalizados
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success mx-2",
                    cancelButton: "btn btn-danger mx-2"
                },
                buttonsStyling: false
            });

            // Eliminar reserva
            $('#reservasTable').on('click', '.eliminar', function() {
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
                        }, function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                // Mostrar mensaje de éxito
                                swalWithBootstrapButtons.fire({
                                    title: "¡Eliminado!",
                                    text: `La reserva de ${nombre} ha sido eliminada con éxito.`,
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

            // Limpiar datos para nueva categoría
            $('#btnAgregarReserva').click(function() {
                tituloModal.textContent = "Nueva Reserva";
                textoModal.textContent = "Agregar Reserva";

                $('#modalFormulario').modal('show');


            });


        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>

</html>