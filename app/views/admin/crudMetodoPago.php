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
    case 'listarMetodo':
        listarMetodo($conn); // Función para listar los metodosPago
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}

// Función para listar metodosPago
function listarMetodo($conn)
{
    // Consulta SQL para obtener todos los metodosPago 
    $query = "SELECT * FROM audio_system.descripcionpago;";

    // Ejecutar la consulta
    $result = $conn->query($query);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
        return;
    }

    // Si la consulta tiene resultados
    if ($result->num_rows > 0) {
        $metodosPago = [];
        while ($row = $result->fetch_assoc()) {
            $metodosPago[] = $row;
        }

        // Configurar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode(['data' => $metodosPago]);
    } else {
        echo json_encode(['error' => 'No se encontraron metodosPago']);
    }
}
