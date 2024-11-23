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



$conn->close();
