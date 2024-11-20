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
    case 'llamarLista':
        llamarLista($conn);
        break;
    case 'crearCliente':
        crearCliente($conn);
        break;
    case 'eliminarCliente':
        eliminarCliente($conn);
        break;
    case 'actualizarCliente':
        actualizarCliente($conn);
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}

// Función para listar clientes
function llamarLista($conn)
{
    $query = "SELECT * FROM clientes";
    $result = $conn->query($query);

    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['data' => $clientes]);
}

// Función para crear un cliente
function crearCliente($conn)
{
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $dni = $_POST['dni'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO clientes (nombre_cliente, direccion, telefono, email, dni, password) VALUES 
              ('$nombre', '$direccion', '$telefono', '$email', '$dni', '$password')";

    if ($conn->query($query)) {
        echo json_encode(["success" => "Cliente creado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al crear cliente: " . $conn->error]);
    }
}

// Función para eliminar un cliente
function eliminarCliente($conn)
{
    $id = $_POST['id'];

    $query = "DELETE FROM clientes WHERE id_cliente = $id";
    if ($conn->query($query)) {
        echo json_encode(["success" => "Cliente eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar cliente: " . $conn->error]);
    }
}

// Función para actualizar un cliente
function actualizarCliente($conn)
{
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $dni = $_POST['dni'];
    $password = $_POST['password'];

    if (!empty($password)) {
        // Si hay una nueva contraseña, hashearla y usarla en la consulta
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE clientes 
                  SET nombre_cliente = ?, direccion = ?, telefono = ?, email = ?, dni = ?, password = ? 
                  WHERE id_cliente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $nombre, $direccion, $telefono, $email, $dni, $passwordHash, $id);
    } else {
        // Si no se proporciona una nueva contraseña, no actualizar ese campo
        $query = "UPDATE clientes 
                  SET nombre_cliente = ?, direccion = ?, telefono = ?, email = ?, dni = ? 
                  WHERE id_cliente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $nombre, $direccion, $telefono, $email, $dni, $id);
    }

    if ($stmt) {
        if ($stmt->execute()) {
            echo json_encode(["success" => "Cliente actualizado correctamente"]);
        } else {
            echo json_encode(["error" => "Error al actualizar cliente: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
    }
}



$conn->close();
