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
    case 'listarProveedor':
        listarProveedor($conn);
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}

// Función para listar categorías
function listarProveedor($conn)
{
    $query = "SELECT * FROM proveedores";
    $result = $conn->query($query);

    $proveedores = [];
    while ($row = $result->fetch_assoc()) {
        $proveedores[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['data' => $proveedores]);
}


$conn->close();
