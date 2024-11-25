// Cargar los métodos de pago en el select
function cargarMetodosPago() {
    $.ajax({
        url: 'crudMetodoPago.php?action=listarMetodo', // URL para obtener los métodos de pago
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            const $metodoPagoSelect = $('#metodoPago');
            $metodoPagoSelect.empty(); // Limpia las opciones actuales
            $metodoPagoSelect.append('<option value="" disabled selected>Seleccione un método de pago</option>'); // Placeholder

            response.data.forEach(metodo => {
                $metodoPagoSelect.append(`
            <option value="${metodo.idPago}">${metodo.descripcionPago} (${metodo.metodoPago})</option>
        `);
            });
        },
        error: function (xhr) {
            console.error('Error al cargar los métodos de pago:', xhr);
        }
    });
}

// Evento para capturar la selección y asignar el ID del método de pago
$('#metodoPago').on('change', function () {
    const idMetodoPago = $(this).val(); // Obtener el ID seleccionado
    $('#idMetodoPago').val(idMetodoPago); // Asignar al input oculto
});

// Llamar a la función al cargar la página
$(document).ready(function () {
    cargarMetodosPago();
});
