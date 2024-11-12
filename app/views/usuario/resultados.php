<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $ampm = $_POST['ampm'];
    $duracion = $_POST['duracion'];
    $distritoId = $_POST['distrito']; // ID del distrito
    $ubicacion = $_POST['ubicacion'];
    $telefono = $_POST['telefono'];
    $metodoPagoId = $_POST['metodoPago']; // ID del método de pago

    // Obtener nombre del archivo subido (comprobante de pago)
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0) {
        $nombreArchivo = $_FILES['comprobante']['name'];
    } else {
        $nombreArchivo = "No se subió archivo.";
    }

    // Mostrar los resultados
    echo "<h2>Detalles de la Reserva</h2>";
    echo "<p><strong>Fecha del servicio:</strong> $fecha</p>";
    echo "<p><strong>Hora de asistencia:</strong> $hora $ampm</p>";
    echo "<p><strong>Duración del servicio:</strong> $duracion horas</p>";
    echo "<p><strong>ID del distrito:</strong> $distritoId</p>";
    echo "<p><strong>Dirección:</strong> $ubicacion</p>";
    echo "<p><strong>Teléfono de contacto:</strong> $telefono</p>";
    echo "<p><strong>ID del método de pago:</strong> $metodoPagoId</p>";
    echo "<p><strong>Nombre del archivo subido:</strong> $nombreArchivo</p>";
}
?>
