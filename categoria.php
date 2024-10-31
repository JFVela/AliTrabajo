<form action="insertar_categoria.php" method="POST" enctype="multipart/form-data">
    <!-- Nombre de la Categoría -->
    <label for="nombre_categoria">Nombre de la Categoría:</label>
    <input type="text" name="nombre_categoria" required><br><br>

    <!-- Descripción -->
    <label for="descripcion_categoria">Descripción de la Categoría:</label>
    <textarea name="descripcion_categoria" required></textarea><br><br>

    <!-- Foto de la Categoría -->
    <label for="foto_categoria">Foto de la Categoría:</label>
    <input type="file" name="foto_categoria" accept="image/*" required><br><br>

    <!-- Botón de envío -->
    <button type="submit">Agregar Categoría</button>
</form>
