<?php
include '../../config/conexionDatos.php';
session_start();
$puerto = $_SERVER['SERVER_PORT'];

if (isset($_POST['total']) && isset($_POST['email'])) {

    // Variables del formulario principal
    $total = $_POST['total'];
    $email = $_POST['email'];

    // Variables del segundo formulario de reservas
    $fechaReserva = $_POST['fechaReservaOculto'];
    $horaReserva = $_POST['horaReservaOculto'];
    $ampm = $_POST['ampmReservaOculto'];
    $duracionReserva = $_POST['duracionReservaOculto'];
    $distritoReserva = $_POST['distritoReservaOculto'];
    $ubicacionReserva = $_POST['ubicacionReservaOculto'];
    $telefonoReserva = $_POST['telefonoReservaOculto'];

    // Variables para el pago
    $metodoPagoId = $_POST['metodoPago'];
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0) {
        $nombreArchivo = $_FILES['comprobante']['name'];
    } else {
        $nombreArchivo = "No se subió archivo.";
    }

    // Obtén el ID del cliente a partir del email
    $query = "SELECT id_cliente FROM clientes WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id_cliente);
    $stmt->fetch();
    $stmt->close();

    // Inserta la cotización en la base de datos
    $query = "INSERT INTO cotizaciones (id_cliente, fecha_cotizacion, total) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($query);
    $total = $total * $duracionReserva; // Calcula el total basado en la duración
    $stmt->bind_param("id", $id_cliente, $total);

    if ($stmt->execute()) {
        // Obtén el ID de la cotización recién insertada
        $id_cotizacion = $stmt->insert_id;

        // Insertar los detalles de la cotización
        $productos = json_decode($_POST['productos'], true);
        $detalle_query = "INSERT INTO cotizacion_detalles (id_cotizacion, id_producto, cantidad, horas_alquiler, subtotal) VALUES (?, ?, ?, ?, ?)";
        $detalle_stmt = $conn->prepare($detalle_query);

        foreach ($productos as $producto) {
            $id_producto = $producto['id_producto'];
            $cantidad = $producto['cantidad'];
            $horas_alquiler = $duracionReserva;
            $subtotal = $producto['subtotal'] * $horas_alquiler;

            $detalle_stmt->bind_param("iiidd", $id_cotizacion, $id_producto, $cantidad, $horas_alquiler, $subtotal);
            $detalle_stmt->execute();
        }
        $detalle_stmt->close();

        // Inserta en la tabla capturapago
        $captura_query = "INSERT INTO capturapago (capturaPago_url, idPago) VALUES (?, ?)";
        $captura_stmt = $conn->prepare($captura_query);
        $captura_stmt->bind_param("si", $nombreArchivo, $metodoPagoId);

        if ($captura_stmt->execute()) {
            // Obtén el último id insertado en capturapago
            $id_captura = $conn->insert_id;

            // Inserta en la tabla reservas
            $reserva_query = "INSERT INTO reservas (id_cotizacion, idDist, idCapt, direccion, fecha_reserva, hora_reserva, ampm, telefonoContacto) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $reserva_stmt = $conn->prepare($reserva_query);
            $reserva_stmt->bind_param("iiissisi", $id_cotizacion, $distritoReserva, $id_captura, $ubicacionReserva, $fechaReserva, $horaReserva, $ampm, $telefonoReserva);

            if ($reserva_stmt->execute()) {

                //Mensaje en caso que todo este correcto.
                $_SESSION['tituloMensaje'] = "Reserva Exitosa!";
                $_SESSION['mensaje'] = "Cotización y detalles guardados con éxito.";
                $_SESSION['icono'] = "success";
                $_SESSION['posision'] = "top-end";
                header("Location: http://localhost:" . $puerto . "/app/views/usuario/index.php");
           
            } else {
                echo "Error al guardar la reserva: " . $conn->error;
            }
            $reserva_stmt->close();
        } else {
            echo "Error al guardar la captura de pago: " . $conn->error;
        }
        $captura_stmt->close();
    } else {
        echo "Error al guardar la cotización: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
