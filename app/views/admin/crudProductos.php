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
    case 'listarProductos':
        listarProductos($conn); // Función para listar los productos
        break;
    case 'crearProducto':
        //crearProducto($conn); // Función para crear un nuevo producto
        break;
    case 'eliminarProducto':
        eliminarProducto($conn); // Función para eliminar un producto
        break;
    case 'actualizarProducto':
        //actualizarProducto($conn); // Función para actualizar un producto
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}


// Función para listar productos
function listarProductos($conn)
{
    // Consulta SQL para obtener todos los productos y los datos completos de categorías y proveedores
    $query = "  SELECT 
                    productos.id_producto, 
                    productos.id_proveedor, 
                    productos.id_categoria, 
                    productos.nombre, 
                    productos.desc, 
                    productos.precio_hr, 
                    productos.stock, 
                    productos.foto, 
                    categorias.nombre_categoria, 
                    proveedores.nomb_empresa 
                FROM productos 
                JOIN categorias ON productos.id_categoria = categorias.id_categoria 
                JOIN proveedores ON productos.id_proveedor = proveedores.id_proveedor";

    // Ejecutar la consulta
    $result = $conn->query($query);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
        return;
    }

    // Si la consulta tiene resultados
    if ($result->num_rows > 0) {
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }

        // Configurar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode(['data' => $productos]);
    } else {
        echo json_encode(['error' => 'No se encontraron productos']);
    }
}

// Función para eliminar un producto
function eliminarProducto($conn)
{
    $id = $_POST['id'];

    // Consulta SQL para eliminar el producto
    $query = "DELETE FROM productos WHERE id_producto = $id";

    if ($conn->query($query)) {
        echo json_encode(["success" => "Producto eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar producto: " . $conn->error]);
    }
}


$conn->close();
