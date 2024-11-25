//Variables globales
let textoModal = document.getElementById("textoDinamico");
let tituloModal = document.getElementById("tituloModal");

// Tabla Ajax
$(document).ready(function () {
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
                    data-idDist="${row.idDist}"
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
                        table.ajax.reload(); // Recargar la tabla de reservas
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
        console.log()

        $('#modalEditar').modal('show');
    });

    // Limpiar datos para nueva categoría
    $('#btnAgregarReserva').click(function () {
        tituloModal.textContent = "Nueva Reserva";
        textoModal.textContent = "Agregar Reserva";
        $('#modalFormulario').modal('show');

    });

});
