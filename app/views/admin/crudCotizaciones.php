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
    case 'llamarCotizaciones':
        llamarCotizaciones($conn);
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
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}

// Función para listar cotizaciones
function llamarCotizaciones($conn)
{
    $query = "
        SELECT 
            c.id_cotizacion, 
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

// Función para eliminar una cotización
function eliminarCotizacion($conn)
{
    $id = $_POST['id'];

    $query = "DELETE FROM cotizaciones WHERE id_cotizacion = $id";
    if ($conn->query($query)) {
        echo json_encode(["success" => "Cotización eliminada correctamente"]);
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

$conn->close();
