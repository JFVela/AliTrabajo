<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Producto</title>
</head>

<body>
    <h2>Agregar Producto</h2>
    <form action="insertar_producto.php" method="POST" enctype="multipart/form-data">
        <!-- Nombre del Producto -->
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" name="nombre" required><br><br>

        <!-- Descripción -->
        <label for="desc">Descripción del Producto:</label>
        <textarea name="desc" required></textarea><br><br>

        <!-- Precio por hora -->
        <label for="precio_hr">Precio por Hora:</label>
        <input type="number" name="precio_hr" step="0.01" required><br><br>

        <!-- Stock -->
        <label for="stock">Stock:</label>
        <input type="number" name="stock" required><br><br>

        <!-- Proveedor -->
        <label for="id_proveedor">Proveedor:</label>
        <select name="id_proveedor" required>
            <?php
            // Este bloque PHP obtiene los proveedores de la base de datos y crea las opciones del select
            require 'conexion.php'; // Archivo de conexión
            $query = "SELECT id_proveedor, nomb_empresa FROM proveedores";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id_proveedor'] . "'>" . $row['nomb_empresa'] . "</option>";
            }
            ?>
        </select><br><br>

        <!-- Categoría -->
        <label for="id_categoria">Categoría:</label>
        <select name="id_categoria" required>
            <?php
            // Este bloque PHP obtiene las categorías de la base de datos y crea las opciones del select
            $query = "SELECT id_categoria, nombre_categoria FROM categorias";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id_categoria'] . "'>" . $row['nombre_categoria'] . "</option>";
            }
            ?>
        </select><br><br>

        <!-- Foto del Producto -->
        <label for="foto">Foto del Producto:</label>
        <input type="file" name="foto" accept="image/*" required><br><br>

        <!-- Botón de envío -->
        <button type="submit">Agregar Producto</button>
    </form>

</body>

</html>