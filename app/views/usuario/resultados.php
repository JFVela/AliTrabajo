<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Obteneer el name del primer formulario de reservas
    $fechaReserva = $_POST['fechaReservaOculto'];
    $horaReserva = $_POST['horaReservaOculto'];
    $ampm = $_POST['ampmReservaOculto'];
    $duracionReserva = $_POST['duracionReservaOculto'];
    $distritoReserva = $_POST['distritoReservaOculto'];
    $ubicacionReserva = $_POST['ubicacionReservaOculto'];
    $telefonoReserva = $_POST['telefonoReservaOculto'];

    // ID del método de pago
    $metodoPagoId = $_POST['metodoPago']; 
    // Obtener nombre del archivo subido (comprobante de pago)
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0) {
        $nombreArchivo = $_FILES['comprobante']['name'];
    } else {
        $nombreArchivo = "No se subió archivo.";
    }


    // Mostrar los valores (o realizar la acción que necesites con ellos)
    echo "Fecha de reserva: " . htmlspecialchars($fechaReserva) . "<br>";
    echo "Hora de reserva: " . htmlspecialchars($horaReserva) . " " . htmlspecialchars($ampm) . "<br>";
    echo "Duración de reserva: " . htmlspecialchars($duracionReserva) . "<br>";
    echo "Distrito de reserva: " . htmlspecialchars($distritoReserva) . "<br>";
    echo "Ubicación de reserva: " . htmlspecialchars($ubicacionReserva) . "<br>";
    echo "Teléfono de reserva: " . htmlspecialchars($telefonoReserva) . "<br>";
    // Mostrar los resultados
    echo "<p><strong>ID del método de pago:</strong> $metodoPagoId</p>";
    echo "<p><strong>Nombre del archivo subido:</strong> $nombreArchivo</p>";
}
