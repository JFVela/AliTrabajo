<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            margin-top: 100px;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 100px;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            flex-basis: calc(30% - 20px);
            /* 3 cards per row */
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .foto_categoria {
            height: 200px;
            /* Ajusta la altura deseada */
            width: 100%;
            /* Asegura que la imagen ocupe todo el ancho */
            object-fit: cover;
            /* Asegura que la imagen cubra el contenedor sin distorsión */
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card-body {
            padding: 20px;
            flex-grow: 1;
            /* Permite que el contenido crezca para llenar el espacio */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        #siguiente-btn {
            display: none;
            /* Esconde el botón "Siguiente" por defecto */
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #siguiente-btn:hover {
            background-color: #218838;
        }
    </style>
    <link rel="stylesheet" href="/public/css/usuario/includes.css">
    <script>
        function mostrarSiguiente(idCategoria) {
            // Redirigir a Planes.php con el ID de la categoría
            window.location.href = 'planes.php?id_categoria=' + idCategoria;
        }
    </script>
</head>

<body>

    <?php
    include '../../../includes/header.php';
    include '../../../config/conexionDatos.php';

    // Consulta para obtener las categorías
    $query = "SELECT * FROM audio_system.categorias";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Comienzo de la estructura HTML
        echo '<div class="container">';
        echo '<h2>Selecciona una Categoría</h2>';
        echo '<div class="row">';

        // Bucle a través de los resultados
        while ($categoria = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<img src="../../../uploads/categorias/' . htmlspecialchars($categoria['foto_categoria']) . '" class="foto_categoria" alt="' . htmlspecialchars($categoria['nombre_categoria']) . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($categoria['nombre_categoria']) . '</h5>';
            echo '<button class="btn" onclick="mostrarSiguiente(' . $categoria['id_categoria'] . ')">Elegir</button>'; // Cambiado
            echo '</div>'; // cierre card-body
            echo '</div>'; // cierre card
        }

        echo '</div>'; // cierre row
        echo '<button id="siguiente-btn">Siguiente</button>';
        echo '</div>'; // cierre container

    } else {
        echo "<div class='container'><p>No hay categorías disponibles.</p></div>";
    }

    // Cierre de la conexión
    $conn->close();
    ?>
    <?php
    include '../../../includes/footer.php';
    ?>
</body>

</html>