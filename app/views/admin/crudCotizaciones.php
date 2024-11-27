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
    case 'listarCotizacion':
        listarCotizacion($conn);
        break;
    case 'crearCotizacion':
        crearCotizacion($conn);
        break;
    case 'eliminarCotizacion':
        eliminarCotizacion($conn);
        break;
    case 'actualizarCotizacion':
        actualizarCotizacion($conn);
        break;
    case 'eliminarOtrosRegistros':
        eliminarOtrosRegistros($conn);
        break;
    case 'listarDetalles':
        listarDetalles($conn);
        break;
    case 'eliminarDetalle':
        eliminarDetalle($conn);
        break;
    case 'actualizarDetalle':
        actualizarDetalle($conn);
        break;
    case 'agregarServiciosDetalle':
        agregarServiciosDetalle($conn);
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}


// Función para listar cotizaciones
function listarCotizacion($conn)
{
    $query = "
        SELECT 
            c.id_cotizacion, 
            c.id_cliente,
            cl.nombre_cliente, 
            c.fecha_cotizacion, 
            c.total 
        FROM 
            cotizaciones c
        JOIN 
            clientes cl 
        ON 
            c.id_cliente = cl.id_cliente";

    $result = $conn->query($query);

    $cotizaciones = [];
    while ($row = $result->fetch_assoc()) {
        $cotizaciones[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['data' => $cotizaciones]);
}

// Función para crear una cotización
function crearCotizacion($conn)
{
    $id_cliente = $_POST['id_cliente'];
    $fecha = $_POST['fecha'];
    $total = $_POST['total'];

    $query = "INSERT INTO cotizaciones (id_cliente, fecha_cotizacion, total) VALUES 
              ('$id_cliente', '$fecha', '$total')";

    if ($conn->query($query)) {
        echo json_encode(["success" => "Cotización creada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al crear cotización: " . $conn->error]);
    }
}

function eliminarCotizacion($conn)
{
    $id = $_POST['id'];

    // Verificar si existen detalles asociados a esta cotización
    $checkDetallesQuery = "SELECT COUNT(*) FROM cotizacion_detalles WHERE id_cotizacion = $id";
    $resultDetalles = $conn->query($checkDetallesQuery);
    $rowDetalles = $resultDetalles->fetch_row();
    $detalleCount = $rowDetalles[0];

    // Verificar si existen registros en reservas asociados a esta cotización
    $checkReservasQuery = "SELECT COUNT(*) FROM reservas WHERE id_cotizacion = $id";
    $resultReservas = $conn->query($checkReservasQuery);
    $rowReservas = $resultReservas->fetch_row();
    $reservaCount = $rowReservas[0];

    if ($detalleCount > 0 || $reservaCount > 0) {
        // Si existen detalles o reservas, notificar al frontend
        echo json_encode([
            "success" => false,
            "message" => "Esta cotización depende de otros registros. ¿Desea eliminar también los detalles y reservas asociados?"
        ]);
        return;
    }

    // Eliminar la cotización si no tiene dependencias
    $query = "DELETE FROM cotizaciones WHERE id_cotizacion = $id";
    if ($conn->query($query)) {
        echo json_encode(["success" => true, "message" => "Cotización eliminada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar cotización: " . $conn->error]);
    }
}

// Función para actualizar una cotización
function actualizarCotizacion($conn)
{
    $id = $_POST['id'];
    $id_cliente = $_POST['id_cliente'];
    $fecha = $_POST['fecha'];
    $total = $_POST['total'];

    $query = "UPDATE cotizaciones 
              SET id_cliente = ?, fecha_cotizacion = ?, total = ? 
              WHERE id_cotizacion = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isdi", $id_cliente, $fecha, $total, $id);

    if ($stmt) {
        if ($stmt->execute()) {
            echo json_encode(["success" => "Cotización actualizada correctamente"]);
        } else {
            echo json_encode(["error" => "Error al actualizar cotización: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
    }
}

function eliminarOtrosRegistros($conn)
{
    $id = $_POST['id'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Eliminar los detalles de cotización
        $deleteDetallesQuery = "DELETE FROM cotizacion_detalles WHERE id_cotizacion = $id";
        $conn->query($deleteDetallesQuery);

        // Eliminar las reservas 
        $deleteReservasQuery = "DELETE FROM reservas WHERE id_cotizacion = $id";
        $conn->query($deleteReservasQuery);

        // Eliminar la fila de la cotización en la tabla cotizaciones
        $deleteCotizacionQuery = "DELETE FROM cotizaciones WHERE id_cotizacion = $id";
        $conn->query($deleteCotizacionQuery);

        // Si todo va bien, confirmamos la transacción
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Cotización, detalles y reservas eliminados correctamente"]);
    } catch (Exception $e) {
        // Si ocurre un error, revertimos la transacción
        $conn->rollback();
        echo json_encode(["error" => "Error al eliminar cotización, detalles o reservas: " . $e->getMessage()]);
    }
}

// Función para listar detalles por el id de la cotizacion
function listarDetalles($conn)
{
    $idCotizacion = isset($_POST['id_cotizacion']) ? intval($_POST['id_cotizacion']) : 0;

    // Validar el ID
    if ($idCotizacion <= 0) {
        echo json_encode(['data' => []]);
        return;
    }

    $query = "SELECT 
                cd.id_detalle,
                cd.id_cotizacion,
                p.id_producto,
                p.nombre AS nombre_producto,
                p.id_proveedor,
                pr.nomb_empresa AS nombre_proveedor,
                pr.nomb_contacto AS contacto_proveedor,
                p.id_categoria,
                c.nombre_categoria AS nombre_categoria,
                p.precio_hr,
                p.foto,
                cd.cantidad,
                cd.horas_alquiler,
                cd.subtotal
            FROM cotizacion_detalles cd
            INNER JOIN productos p ON cd.id_producto = p.id_producto
            INNER JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE cd.id_cotizacion = $idCotizacion";

    $result = $conn->query($query);

    $detalleCotizacion = [];
    while ($row = $result->fetch_assoc()) {
        $detalleCotizacion[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['data' => $detalleCotizacion]);
}


function eliminarDetalle($conn)
{
    $idDetalle = $_POST['id_detalle'];
    $idCotizacion = $_POST['id_cotizacion'];

    // Obtener el subtotal del detalle a eliminar
    $querySubtotal = "SELECT subtotal FROM cotizacion_detalles WHERE id_detalle = $idDetalle";
    $resultSubtotal = $conn->query($querySubtotal);

    if ($resultSubtotal && $resultSubtotal->num_rows > 0) {
        $detalle = $resultSubtotal->fetch_assoc();
        $subtotal = $detalle['subtotal'];

        // Restar el subtotal del total en la cotización
        $queryUpdateTotal = "UPDATE cotizaciones SET total = total - $subtotal WHERE id_cotizacion = $idCotizacion";
        $conn->query($queryUpdateTotal);

        // Eliminar el detalle
        $queryEliminarDetalle = "DELETE FROM cotizacion_detalles WHERE id_detalle = $idDetalle";
        if ($conn->query($queryEliminarDetalle)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => "Error al eliminar detalle: " . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => "Detalle no encontrado."]);
    }
}

// Función para actualizar una cotización
function actualizarDetalle($conn)
{
    $idDetalle = $_POST['id'];
    $cantidad = $_POST['cantidad'];
    $horas = $_POST['horas'];
    $newSubtotal = $_POST['subtotal'];

    // Obtener el subtotal actual del detalle
    $queryOldSubtotal = "SELECT subtotal, id_cotizacion FROM cotizacion_detalles WHERE id_detalle = ?";
    $stmtOld = $conn->prepare($queryOldSubtotal);
    $stmtOld->bind_param("i", $idDetalle);
    $stmtOld->execute();
    $result = $stmtOld->get_result();
    $row = $result->fetch_assoc();

    $oldSubtotal = $row['subtotal'];
    $idCotizacion = $row['id_cotizacion'];
    $stmtOld->close();

    // Calcular la diferencia
    $diferencia = $newSubtotal - $oldSubtotal;

    // Actualizar el subtotal del detalle
    $queryUpdateDetalle = "UPDATE cotizacion_detalles 
                           SET cantidad = ?, horas_alquiler = ?, subtotal = ? 
                           WHERE id_detalle = ?";
    $stmtUpdate = $conn->prepare($queryUpdateDetalle);
    $stmtUpdate->bind_param("iidi", $cantidad, $horas, $newSubtotal, $idDetalle);

    if ($stmtUpdate->execute()) {
        // Actualizar el total de la cotización
        $queryUpdateCotizacion = "UPDATE cotizaciones 
                                  SET total = total + ? 
                                  WHERE id_cotizacion = ?";
        $stmtUpdateCotizacion = $conn->prepare($queryUpdateCotizacion);
        $stmtUpdateCotizacion->bind_param("di", $diferencia, $idCotizacion);

        if ($stmtUpdateCotizacion->execute()) {
            echo json_encode(["success" => "Detalle y cotización actualizados correctamente"]);
        } else {
            echo json_encode(["error" => "Error al actualizar el total de la cotización: " . $stmtUpdateCotizacion->error]);
        }
        $stmtUpdateCotizacion->close();
    } else {
        echo json_encode(["error" => "Error al actualizar el detalle: " . $stmtUpdate->error]);
    }
    $stmtUpdate->close();
}

// Función para crear una cotización
function agregarServiciosDetalle($conn)
{
    // Obtener datos del POST
    $idCotizacion = intval($_POST['idCotizacion']); // ID de la cotización
    $horas = intval($_POST['horas']); // Horas de alquiler
    $productosSeleccionados = json_decode($_POST['productosSeleccionados'], true); // Convertir JSON a array
    $totalFinal = floatval($_POST['totalFinal']); // Total del formulario

    // Validar que exista el ID de cotización
    if ($idCotizacion <= 0 || empty($productosSeleccionados)) {
        echo json_encode(["error" => "Datos inválidos."]);
        return;
    }

    // Iniciar la transacción para asegurar consistencia
    $conn->begin_transaction();
    try {
        // Insertar los detalles en la tabla cotizacion_detalles
        $queryDetalle = "INSERT INTO cotizacion_detalles (id_cotizacion, id_producto, cantidad, horas_alquiler, subtotal) VALUES (?, ?, ?, ?, ?)";
        $stmtDetalle = $conn->prepare($queryDetalle);

        foreach ($productosSeleccionados as $producto) {
            $idProducto = intval($producto['id']);
            $cantidad = intval($producto['cantidad']);
            $subtotal = floatval($producto['precio']) * $cantidad * $horas; // Calcular subtotal

            $stmtDetalle->bind_param('iiids', $idCotizacion, $idProducto, $cantidad, $horas, $subtotal);
            $stmtDetalle->execute();
        }

        // Actualizar el total en la tabla cotizaciones
        $queryUpdateCotizacion = "UPDATE cotizaciones SET total = total + ? WHERE id_cotizacion = ?";
        $stmtUpdate = $conn->prepare($queryUpdateCotizacion);
        $stmtUpdate->bind_param('di', $totalFinal, $idCotizacion);
        $stmtUpdate->execute();

        // Confirmar la transacción
        $conn->commit();

        echo json_encode(["success" => "Servicios agregados correctamente."]);
    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        $conn->rollback();
        echo json_encode(["error" => "Error al agregar servicios: " . $e->getMessage()]);
    }
}


$conn->close();
