<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_categoria = $_POST['nombre_categoria'];
    $descripcion_categoria = $_POST['descripcion_categoria'];

    // Manejo de la imagen
    $foto_categoria = $_FILES['foto_categoria'];
    $nombre_foto_original = basename($foto_categoria['name']);

    // Generar un nombre aleatorio para la imagen
    $numero_aleatorio = mt_rand(10000, 99999); // Número aleatorio entre 10000 y 99999
    $nuevo_nombre_foto = $nombre_categoria . "-" . str_pad($numero_aleatorio, 5, "0", STR_PAD_LEFT) . ".jpg"; // Cambiar extensión según tipo de imagen

    $ruta_foto_categoria = "uploads/categorias/" . $nuevo_nombre_foto;
    move_uploaded_file($foto_categoria['tmp_name'], $ruta_foto_categoria); // Guarda la imagen en la carpeta 'uploads/categorias'

    // Inserción en la base de datos
    $sql = "INSERT INTO categorias (`nombre_categoria`, `descripcion_categoria`, `foto_categoria`) 
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre_categoria, $descripcion_categoria, $ruta_foto_categoria);

    if ($stmt->execute()) {
        echo "Categoría agregada exitosamente.";
    } else {
        echo "Error al agregar la categoría: " . $stmt->error; // Cambié a $stmt->error para obtener el error específico
    }

    $stmt->close();
    $conn->close();
}
?>
