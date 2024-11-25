document.addEventListener('DOMContentLoaded', function () {
    // Restringir la fecha al día de hoy en adelante
    const fechaInput = document.getElementById('fecha');
    const hoy = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
    fechaInput.setAttribute('min', hoy);

    // Validar el teléfono
    const telefonoInput = document.getElementById('telefono');
    telefonoInput.addEventListener('input', function () {
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
