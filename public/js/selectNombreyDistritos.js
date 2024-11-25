$(document).ready(function () {
    // Inicializar el select2 para Nombre de Usuario
    $('#nombreUsuario').select2({
        placeholder: "Seleccione un usuario",
        dropdownParent: $('#modalFormulario'), // Evita conflictos de estilo
        allowClear: true,
        ajax: {
            url: 'crudReservas.php?action=listarUsuarios', // URL para obtener usuarios
            dataType: 'json',
            method: 'GET',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                }; // Enviar el término de búsqueda
            },
            processResults: function (data) {
                // Transformar la respuesta para Select2
                return {
                    results: data.data.map(function (cliente) {
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
    $('#nombreUsuario').on('select2:select', function (e) {
        const selectedId = e.params.data.id;
        $('#idCliente').val(selectedId); // Asignar ID al input oculto
    });

    // Para el select del distrito, con búsqueda dinámica
    $('#distrito').select2({
        placeholder: "Seleccione un distrito",
        dropdownParent: $('#modalFormulario'), // Evita conflictos de estilo
        allowClear: true,
        ajax: {
            url: 'crudUbicacion.php?action=listarDistrito', // URL para buscar distritos
            dataType: 'json',
            method: 'GET',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // Enviar el término de búsqueda
                };
            },
            processResults: function (data) {
                // Transformar la respuesta para Select2
                return {
                    results: data.data.map(function (distrito) {
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
    $('#distrito').on('select2:select', function (e) {
        const selectedId = e.params.data.id;
        $('#idDistrito').val(selectedId); // Asignar ID al input oculto
    });

    // Función adicional para limpiar inputs (si es necesaria)
    function limpiarInput() {
        $('#idCliente').val(''); // Limpia el input del cliente
    }
});
