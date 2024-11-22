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
        crearProducto($conn); // Función para crear un nuevo producto
        break;
    case 'eliminarProducto':
        eliminarProducto($conn); // Función para eliminar un producto
        break;
    case 'actualizarProducto':
        actualizarProducto($conn); // Función para actualizar un producto
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}

//Funcion crear productos
function crearProducto($conn)
{
    $id_categoria = $_POST['id_categoria'];
    $id_proveedor = $_POST['id_proveedor'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_hr = $_POST['precio_hr'];
    $stock = $_POST['stock'];

    // Procesar la foto
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $uploadDir = '../../../uploads/productos/';
        $foto = uniqid() . '_' . basename($_FILES['foto']['name']);
        $uploadFile = $uploadDir . $foto;

        // Mover el archivo al servidor
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            echo json_encode(["error" => "Error al subir la foto."]);
            return;
        }
    }

    $query = "INSERT INTO productos (id_categoria, id_proveedor, nombre, `desc`, precio_hr, stock, foto) VALUES 
              ('$id_categoria', '$id_proveedor', '$nombre', '$descripcion', '$precio_hr', '$stock', '$foto')";

    if ($conn->query($query)) {
        echo json_encode(["success" => "Producto creado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al crear producto: " . $conn->error]);
    }
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
    // Validar los datos recibidos
    if (!isset($_POST['id']) || !isset($_POST['foto'])) {
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    $id = intval($_POST['id']);
    $fotoExistente = $_POST['foto'];

    $uploadDir = '../../../uploads/productos/';

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

    // Usar consultas preparadas para mayor seguridad
    $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Producto eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar producto: " . $stmt->error]);
    }

    $stmt->close();
}


// Función actualizar producto
function actualizarProducto($conn)
{
    $id = $_POST['id'];
    $id_categoria = $_POST['id_categoria'];
    $id_proveedor = $_POST['id_proveedor'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_hr = $_POST['precio_hr'];
    $stock = $_POST['stock'];
    $fotoExistente = $_POST['fotoExistente'];

    $foto = $fotoExistente; // Por defecto, usa la foto existente

    // Procesar nueva foto si se envió
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $uploadDir = '../../../uploads/productos/';

        // Generar el nuevo nombre de la foto
        $foto = uniqid() . '_' . basename($_FILES['foto']['name']);
        $uploadFile = $uploadDir . $foto;

        // Eliminar la foto existente si es diferente de la predeterminada (por ejemplo, "default.png")
        if ($fotoExistente !== 'default.png' && file_exists($uploadDir . $fotoExistente)) {
            unlink($uploadDir . $fotoExistente);
        }

        // Mover la nueva foto al servidor
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            echo json_encode(["error" => "Error al subir la foto."]);
            return;
        }
    }

    // Actualizar en la base de datos
    $query = "UPDATE productos SET 
              id_categoria = '$id_categoria',
              id_proveedor = '$id_proveedor',
              nombre = '$nombre',
              `desc` = '$descripcion',
              precio_hr = '$precio_hr',
              stock = '$stock',
              foto = '$foto'
              WHERE id_producto = $id";

    if ($conn->query($query)) {
        echo json_encode(["success" => "Producto actualizado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al actualizar producto: " . $conn->error]);
    }
}

$conn->close();
