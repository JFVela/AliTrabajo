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
    case 'listarDistrito':
        listarDistrito($conn); // Función para listar los distrito
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}



// Función para listar distrito con búsqueda
function listarDistrito($conn)
{
    // Obtener el parámetro de búsqueda, si existe
    $searchTerm = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

    // Consulta SQL con búsqueda dinámica usando LIKE
    $query = "  SELECT idDist, Distrito 
                FROM audio_system.distritos
                WHERE Distrito LIKE '%$searchTerm%';
            ";

    // Ejecutar la consulta
    $result = $conn->query($query);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
        return;
    }

    // Si la consulta tiene resultados
    if ($result->num_rows > 0) {
        $distrito = [];
        while ($row = $result->fetch_assoc()) {
            $distrito[] = $row;
        }

        // Configurar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode(['data' => $distrito]);
    } else {
        // Si no hay resultados, devolver un mensaje vacío
        echo json_encode(['data' => []]);
    }
}


$conn->close();
