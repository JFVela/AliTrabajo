<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "audio_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Determinar la acción a realizar
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'listarReservas':
        listarReservas($conn);
        break;
    case 'nuevaReserva':
        nuevaReserva($conn);
        break;
    case 'actualizarReserva':
        actualizarReserva($conn);
        break;
    case 'eliminarReserva':
        eliminarReserva($conn);
        break;
    case 'listarUsuarios':
        listarUsuarios($conn);
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}

// Función para listar reservas
function listarReservas($conn)
{
    $query = "  SELECT 
                    r.id_reserva,
                    r.id_cotizacion,
                    c.id_cliente,
                    cl.nombre_cliente,
                    r.idDist,
                    d.Distrito AS nombre_distrito,
                    p.idProv,
                    p.Provincia AS nombre_provincia,
                    dep.idDepa,
                    dep.Departamento AS nombre_departamento,
                    r.idCapt,
                    cp.capturaPago_url,
                    cp.estadoPago,
                    cp.idPago,
                    dp.descripcionPago,
                    r.direccion AS direccion_reserva,
                    r.fecha_reserva,
                    r.hora_reserva,
                    r.ampm,
                    r.telefonoContacto,
                    r.estado_reserva
                FROM 
                    reservas r
                LEFT JOIN 
                    cotizaciones c ON r.id_cotizacion = c.id_cotizacion
                LEFT JOIN 
                    clientes cl ON c.id_cliente = cl.id_cliente
                LEFT JOIN 
                    distritos d ON r.idDist = d.idDist
                LEFT JOIN 
                    provincias p ON d.idProv = p.idProv
                LEFT JOIN 
                    departamentos dep ON p.idDepa = dep.idDepa
                LEFT JOIN 
                    capturapago cp ON r.idCapt = cp.idCapt
                LEFT JOIN 
                    descripcionpago dp ON cp.idPago = dp.idPago;
            ";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode(['data' => $reservas]);
    } else {
        echo json_encode(['data' => []]);
    }
}

// Función para crear una reserva (vacía por ahora)
function nuevaReserva($conn)
{
    // Obtener los datos enviados desde el cliente
    $idCliente = $_POST['idCliente'];
    $totalFinal = $_POST['totalFinal'];
    $productosSeleccionados = json_decode($_POST['productosSeleccionados'], true);
    $horasElegidas = $_POST['horasAlquiler'];
    $foto = $_FILES['capturaPago'];
    $idMetodoPago = $_POST['idMetodoPago'];
    $idDistrito = $_POST['idDistrito'];
    $direccion = $_POST['direccion'];
    $fechaReserva = $_POST['fechaReserva'];
    $horaReserva = $_POST['horaReserva'];
    $ampm = $_POST['ampm'];
    $telefonoContacto = $_POST['telefonoContacto'];

    try {
        // Iniciar una transacción
        $conn->begin_transaction();

        // 1. Insertar en la tabla `cotizaciones`
        $stmt = $conn->prepare("INSERT INTO cotizaciones (id_cliente, fecha_cotizacion, total) VALUES (?, NOW(), ?)");
        $stmt->bind_param("id", $idCliente, $totalFinal); // Vincular parámetros: "i" para entero, "d" para decimal
        $stmt->execute();
        $idCotizacion = $conn->insert_id; // Obtener el ID generado

        // 2. Insertar en la tabla `cotizacion_detalles`
        foreach ($productosSeleccionados as $producto) {
            $idProducto = $producto['id'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];
            $subtotal = $precio * $cantidad * $horasElegidas;

            $stmt = $conn->prepare("INSERT INTO cotizacion_detalles (id_cotizacion, id_producto, cantidad, horas_alquiler, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiidd", $idCotizacion, $idProducto, $cantidad, $horasElegidas, $subtotal); // Vincular parámetros
            $stmt->execute();
        }

        // 3. Subir la foto de pago y generar el registro en `capturapago`
        $nombreArchivo = uniqid() . '_' . $foto['name'];
        $rutaDestino = "../../../uploads/comprobantes/" . $nombreArchivo;

        if (!move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
            throw new Exception("Error al subir la imagen de pago.");
        }

        $stmt = $conn->prepare("INSERT INTO capturapago (capturaPago_url, idPago) VALUES (?, ?)");
        $stmt->bind_param("si", $nombreArchivo, $idMetodoPago); // Vincular parámetros
        $stmt->execute();
        $idCapt = $conn->insert_id; // Obtener el ID generado

        // 4. Insertar en la tabla `reservas`
        $stmt = $conn->prepare("INSERT INTO reservas (id_cotizacion, idDist, idCapt, direccion, fecha_reserva, hora_reserva, ampm, telefonoContacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisssss", $idCotizacion, $idDistrito, $idCapt, $direccion, $fechaReserva, $horaReserva, $ampm, $telefonoContacto); // Vincular parámetros
        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();

        echo json_encode(["success" => true, "message" => "Reserva creada exitosamente."]);
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}

// Función para actualizar una reserva
function actualizarReserva($conn)
{
    // Verificar que los datos necesarios se hayan enviado
    if (isset($_POST['idReserva'], $_POST['idDist'], $_POST['direccion'], $_POST['fecha'], $_POST['hora'], $_POST['ampm'], $_POST['telf'], $_POST['estado'])) {
        // Obtener datos enviados por el cliente
        $idReserva = $_POST['idReserva'];
        $idDist = $_POST['idDist'];
        $direccion = $_POST['direccion'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $ampm = $_POST['ampm'];
        $telf = $_POST['telf'];
        $estado = $_POST['estado'];
        $fotoExistente = $_POST['fotoExistente'];
        $foto = $fotoExistente;

        // Procesar nueva foto si se envió
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $uploadDir = '../../../uploads/comprobantes/';

            // Generar el nuevo nombre de la foto
            $foto = uniqid() . '_' . basename($_FILES['foto']['name']);
            $uploadFile = $uploadDir . $foto;

            // Eliminar la foto existente si es diferente de la predeterminada (por ejemplo, "default.png")
            if ($fotoExistente !== 'default.png' && file_exists($uploadDir . $fotoExistente)) {
                unlink($uploadDir . $fotoExistente);
            }

            // Mover la nueva foto al servidor
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                echo json_encode(["error" => "Error al subir la foto."]);
                return;
            }
        }

        // Iniciar una transacción para garantizar consistencia
        $conn->begin_transaction();

        try {
            // Preparar la consulta SQL para actualizar la tabla reservas
            $queryReservas = "UPDATE reservas SET idDist = ?, direccion = ?, fecha_reserva = ?, hora_reserva = ?, ampm = ?, telefonoContacto = ?, estado_reserva = ? WHERE id_reserva = ?";

            if ($stmt = $conn->prepare($queryReservas)) {
                $stmt->bind_param('sssssssi', $idDist, $direccion, $fecha, $hora, $ampm, $telf, $estado, $idReserva);

                if (!$stmt->execute()) {
                    throw new Exception("Error al actualizar la tabla reservas: " . $stmt->error);
                }

                $stmt->close();
            } else {
                throw new Exception("Error al preparar la consulta para reservas: " . $conn->error);
            }

            // Actualizar la tabla capturapago con el nuevo nombre de la foto
            $queryCapturaPago = "UPDATE capturapago SET capturaPago_url = ? WHERE idCapt = (SELECT idCapt FROM reservas WHERE id_reserva = ?)";

            if ($stmtCaptura = $conn->prepare($queryCapturaPago)) {
                $stmtCaptura->bind_param('si', $foto, $idReserva);

                if (!$stmtCaptura->execute()) {
                    throw new Exception("Error al actualizar la tabla capturapago: " . $stmtCaptura->error);
                }

                $stmtCaptura->close();
            } else {
                throw new Exception("Error al preparar la consulta para capturapago: " . $conn->error);
            }

            // Confirmar la transacción
            $conn->commit();
            echo json_encode(["success" => true]);
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollback();
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Faltan datos obligatorios."]);
    }
}

// Función para eliminar una reserva
function eliminarReserva($conn)
{
    if (!isset($_POST['id_reserva']) || !isset($_POST['foto'])) {
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    $id_reserva = intval($_POST['id_reserva']);
    $fotoExistente = $_POST['foto'];

    $uploadDir = '../../../uploads/comprobantes/';

    // Eliminar la foto existente si no es la predeterminada
    if ($fotoExistente !== 'default.png') {
        $fotoPath = realpath($uploadDir . $fotoExistente);
        if ($fotoPath && strpos($fotoPath, realpath($uploadDir)) === 0 && file_exists($fotoPath)) {
            unlink($fotoPath);
        } else {
            echo json_encode(["error" => "No se pudo encontrar o eliminar la foto."]);
            return;
        }
    }

    $stmt = $conn->prepare("DELETE FROM reservas WHERE id_reserva = ?");
    $stmt->bind_param("i", $id_reserva);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Reserva eliminada correctamente."]);
    } else {
        echo json_encode(["error" => "Error al eliminar la reserva: " . $stmt->error]);
    }

    $stmt->close();
}

// Función para listar clientes con búsqueda
function listarUsuarios($conn)
{
    $search = isset($_GET['q']) ? $_GET['q'] : ''; // Obtener la palabra de búsqueda
    $query = "SELECT * FROM clientes WHERE nombre_cliente LIKE '%$search%'";
    $result = $conn->query($query);

    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['data' => $clientes]);
}


$conn->close();
