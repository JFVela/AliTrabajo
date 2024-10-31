<?php
    session_start();
    if (isset($_SESSION['emailcliente'])) {
        echo '<h3 class="links__a">Hola ' . $_SESSION['emailcliente'] . '!</h3>';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin: 10px;
            flex-basis: calc(25% - 20px);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .fotito {
            height: 200px;
            width: 100%;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .card-text {
            margin-bottom: 15px;
            color: #555;
        }

        .input-cantidad {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
        }

        .btn-agregar {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-agregar:hover {
            background-color: #0056b3;
        }

        .resultado-container {
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .hidden {
            display: none;
        }
    </style>
    <script>
        let ArrayID = [];
        let IDCantidad = [];
        let PrecioTotal = [];

        function agregarProducto(id, cantidad, precio) {
            ArrayID.push(id);
            IDCantidad.push(cantidad);
            PrecioTotal.push(precio * cantidad);

            let output = '';
            let totalFinal = 0;

            for (let i = 0; i < ArrayID.length; i++) {
                output += 'ID Producto: ' + ArrayID[i] + ', Cantidad: ' + IDCantidad[i] + ', Subtotal: $' + PrecioTotal[i].toFixed(2) + '<br>';
                totalFinal += PrecioTotal[i];
            }

            output += '<strong>Total Final: $' + totalFinal.toFixed(2) + '</strong>';
            document.getElementById('resultado').innerHTML = output;

            // Establecer el total en el campo oculto
            document.getElementById('total').value = totalFinal.toFixed(2);

            // Mostrar u ocultar el formulario dependiendo del total
            const formFinalizar = document.getElementById('form-finalizar');
            if (totalFinal > 0) {
                formFinalizar.classList.remove('hidden'); // Mostrar el formulario
            } else {
                formFinalizar.classList.add('hidden'); // Ocultar el formulario
            }

            // Guardar detalles de los productos en un formato que se puede enviar
            document.getElementById('productos').value = JSON.stringify(ArrayID.map((id, index) => ({
                id_producto: id,
                cantidad: IDCantidad[index],
                subtotal: PrecioTotal[index]
            })));
        }
    </script>
</head>

<body>

    <?php
    include '../../../config/conexionDatos.php';

    $id_categoria = isset($_GET['id_categoria']) ? intval($_GET['id_categoria']) : 0;
    $query = "SELECT * FROM productos WHERE id_categoria = $id_categoria";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<div class="container">';
        echo '<div class="row">';

        while ($producto = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<img src="../../../' . htmlspecialchars($producto['foto']) . '" class="fotito" alt="' . htmlspecialchars($producto['nombre']) . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($producto['nombre']) . '</h5>';
            echo '<p class="card-text">Precio: $' . htmlspecialchars($producto['precio_hr']) . '</p>';
            echo '<input type="number" class="input-cantidad" min="1" value="1" placeholder="Cantidad" id="cantidad_' . $producto['id_producto'] . '">';
            echo '<button class="btn-agregar" onclick="agregarProducto(' . $producto['id_producto'] . ', document.getElementById(\'cantidad_' . $producto['id_producto'] . '\').value, ' . htmlspecialchars($producto['precio_hr']) . ')">Agregar</button>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo "No hay productos disponibles para esta categoría.";
    }

    $conn->close();
    ?>

    <div class="resultado-container">
        <h4>Productos Agregados:</h4>
        <div id="resultado"></div>
        <form method="post" action="../../controllers/insert_cotizacion.php" class="hidden" id="form-finalizar">
            <input type="hidden" name="total" id="total" value="0">
            <input type="hidden" name="productos" id="productos" value=''>
            <input type="hidden" name="email" value="<?php echo $_SESSION['emailcliente']; ?>">
            <button type="submit" class="btn-agregar">Finalizar</button>
        </form>

    </div>

    <script>
        // Hacer visible el formulario cuando se hace clic en "Finalizar"
        document.querySelector('.btn-agregar.cotizar').onclick = function() {
            document.querySelector('form').submit();
        }
    </script>
</body>

</html>