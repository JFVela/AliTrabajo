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
    case 'crearReserva':
        crearReserva($conn);
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
function crearReserva($conn)
{
    echo json_encode(["info" => "Función de creación aún no implementada."]);
}

// Función para actualizar una reserva (vacía por ahora)
function actualizarReserva($conn)
{
    echo json_encode(["info" => "Función de actualización aún no implementada."]);
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
