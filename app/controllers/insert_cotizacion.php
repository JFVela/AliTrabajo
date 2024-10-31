<?php
include '../../config/conexionDatos.php';
session_start();
$puerto = $_SERVER['SERVER_PORT'];

if (isset($_POST['total']) && isset($_POST['email'])) {
    $total = $_POST['total'];
    $email = $_POST['email'];

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
    $stmt->bind_param("id", $id_cliente, $total);
    if ($stmt->execute()) {
        // Obtén el ID de la cotización recién insertada
        $id_cotizacion = $stmt->insert_id;

        // Insertar los detalles de la cotización
        // Suponiendo que el formato de los datos enviados es correcto
        $productos = json_decode($_POST['productos'], true); // Recibimos un JSON con los productos

        $detalle_query = "INSERT INTO cotizacion_detalles (id_cotizacion, id_producto, cantidad, horas_alquiler, subtotal) VALUES (?, ?, ?, ?, ?)";
        $detalle_stmt = $conn->prepare($detalle_query);

        foreach ($productos as $producto) {
            $id_producto = $producto['id_producto'];
            $cantidad = $producto['cantidad'];
            $horas_alquiler = 1; // Default to 1 hour
            $subtotal = $producto['subtotal'];

            $detalle_stmt->bind_param("iiidd", $id_cotizacion, $id_producto, $cantidad, $horas_alquiler, $subtotal);
            $detalle_stmt->execute();
        }

        $detalle_stmt->close();

        $_SESSION['tituloMensaje'] = "Guardado con Éxito";
        $_SESSION['mensaje'] = "Cotización y detalles guardados con éxito.";
        $_SESSION['icono'] = "success";
        $_SESSION['posision'] = "top-end";
        header("Location: http://localhost:" . $puerto . "/app/views/usuario/index.php");
    } else {
        echo "Error al guardar la cotización: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
