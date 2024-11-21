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
    case 'llamarCategorias':
        llamarCategorias($conn);
        break;
    case 'crearCategoria':
        crearCategoria($conn);
        break;
    case 'eliminarCategoria':
        eliminarCategoria($conn);
        break;
    case 'actualizarCategoria':
        actualizarCategoria($conn);
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}

// Función para listar categorías
function llamarCategorias($conn)
{
    $query = "SELECT * FROM categorias";
    $result = $conn->query($query);

    $categorias = [];
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['data' => $categorias]);
}

// Función para crear una categoría
function crearCategoria($conn)
{
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    // Procesar la foto
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        //Cuando lo pongas en otra carpeta que termine en "/" 
        $uploadDir = '../../../uploads/categorias/';

        $foto = uniqid() . '_' . basename($_FILES['foto']['name']); // Evitar duplicados
        $uploadFile = $uploadDir . $foto;

        // Mover el archivo al servidor
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            echo json_encode(["error" => "Error al subir la foto."]);
            return;
        }
    }

    $query = "INSERT INTO categorias (nombre_categoria, descripcion_categoria, foto_categoria) VALUES 
              ('$nombre', '$descripcion', '$foto')";

    if ($conn->query($query)) {
        echo json_encode(["success" => "Categoría creada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al crear categoría: " . $conn->error]);
    }
}

// Función para eliminar una categoría
function eliminarCategoria($conn)
{
    $id = $_POST['id'];

    $query = "DELETE FROM categorias WHERE id_categoria = $id";
    if ($conn->query($query)) {
        echo json_encode(["success" => "Categoría eliminada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar categoría: " . $conn->error]);
    }
}

// Función para actualizar una categoría
function actualizarCategoria($conn)
{
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fotoExistente = $_POST['fotoExistente'];

    $foto = $fotoExistente; // Por defecto, usa la foto existente

    // Procesar nueva foto si se envió
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $uploadDir = '../../../uploads/categorias/';

        $foto = uniqid() . '_' . basename($_FILES['foto']['name']);
        $uploadFile = $uploadDir . $foto;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            echo json_encode(["error" => "Error al subir la foto."]);
            return;
        }
    }

    $query = "UPDATE categorias SET 
              nombre_categoria = '$nombre',
              descripcion_categoria = '$descripcion',
              foto_categoria = '$foto'
              WHERE id_categoria = $id";

    if ($conn->query($query)) {
        echo json_encode(["success" => "Categoría actualizada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al actualizar categoría: " . $conn->error]);
    }
}

$conn->close();
