<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $desc = $_POST['desc'];
    $precio_hr = $_POST['precio_hr'];
    $stock = $_POST['stock'];
    $id_proveedor = $_POST['id_proveedor'];
    $id_categoria = $_POST['id_categoria'];

    // Manejo de la imagen
    $foto = $_FILES['foto'];
    $nombre_foto = basename($foto['name']);
    $ruta_foto = "uploads/" . $nombre_foto;
    move_uploaded_file($foto['tmp_name'], $ruta_foto); // Guarda la imagen en la carpeta 'uploads'

    // Inserción en la base de datos
    $sql = "INSERT INTO productos (`id_proveedor`, `id_categoria`, `nombre`, `desc`, `precio_hr`, `stock`, `foto`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissdis", $id_proveedor, $id_categoria, $nombre, $desc, $precio_hr, $stock, $ruta_foto);

    if ($stmt->execute()) {
        echo "Producto agregado exitosamente.";
    } else {
        echo "Error al agregar el producto: " . $stmt->error; // Cambié a $stmt->error para obtener el error específico
    }

    $stmt->close();
    $conn->close();
}
?>
