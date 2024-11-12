<?php
include '../../../includes/header.php';
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
            margin-top: 100px;
            padding: 0;
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
    <style>
        .mensaje-sesion {
            background-color: #f9f9f9;
            /* Color de fondo suave */
            border: 1px solid #ccc;
            /* Borde gris */
            border-radius: 8px;
            /* Bordes redondeados */
            padding: 20px;
            /* Espaciado interno */
            text-align: center;
            /* Centrar texto */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Sombra */
            margin: 20px auto;
            /* Margen automático */
            max-width: 400px;
            /* Ancho máximo */
        }

        .mensaje-sesion h2 {
            color: #333;
            /* Color del título */
            margin-bottom: 15px;
            /* Espacio debajo del título */
        }

        .mensaje-sesion p {
            color: #666;
            /* Color del texto */
            margin-bottom: 20px;
            /* Espacio debajo del texto */
        }

        .boton-iniciar,
        .boton-crear {
            background-color: #007BFF;
            /* Color de fondo del botón */
            color: white;
            /* Color del texto */
            padding: 10px 15px;
            /* Espaciado interno */
            border: none;
            /* Sin borde */
            border-radius: 5px;
            /* Bordes redondeados */
            text-decoration: none;
            /* Sin subrayado */
            margin: 5px;
            /* Margen entre botones */
            transition: background-color 0.3s;
            /* Transición suave */
            display: inline-block;
            /* Mostrar como bloque en línea */
        }

        .boton-iniciar:hover,
        .boton-crear:hover {
            background-color: #0056b3;
            /* Color de fondo al pasar el mouse */
        }
    </style>
    <link rel="stylesheet" href="/public/css/usuario/includes.css">

</head>

<body>

    <?php

    if (!isset($_SESSION['emailcliente'])) { ?>
        <div class="mensaje-sesion">
            <h2>Iniciar Sesión Requerido</h2>
            <p>Para continuar con la cotización, por favor inicie sesión en su cuenta.</p>
            <a href="loginCliente.php" class="boton-iniciar">Iniciar Sesión</a>
            <a href="createUser.php" class="boton-crear">Crear Cuenta</a>
        </div>
    <?php
        include '../../../includes/footer.php';
        return;
    }
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
            <br>

            <!-- Nuevo input para seleccionar horas -->
            <input type="number" class="horasAlquiladas" min="1" max="10" value="1" placeholder="Horas de alquiler" id="HorasAlquiladas">

            <!-- Botón Calcular que solo calcula el costo sin enviar el formulario -->
            <button type="button" class="btn-agregar calcular" onclick="calcularCosto()">Calcular</button>

            <br>
            <!-- Div para mostrar el Costo Total del Servicio -->
            <div id="costoTotalServicio"></div>

            <br>
            <!-- Botón Siguiente para enviar el formulario -->
            <button type="submit" class="btn-agregar">Siguiente</button>
        </form>
    </div>

    <style>
        .horasAlquiladas {
            border-radius: 5px;
            height: 36px;
            width: 100px;
        }
    </style>

    <?php
    include '../../../includes/footer.php';
    ?>

    <script>
        // Hacer visible el formulario cuando se hace clic en "Finalizar"
        document.querySelector('.btn-agregar.cotizar').onclick = function() {
            document.querySelector('form').submit();
        }
    </script>
    <script>
        let ArrayID = [];
        let IDCantidad = [];
        let PrecioTotal = [];
        let totalFinal = 0;

        function agregarProducto(id, cantidad, precio) {
            ArrayID.push(id);
            IDCantidad.push(cantidad);
            PrecioTotal.push(precio * cantidad);

            let output = '';
            totalFinal = 0;

            for (let i = 0; i < ArrayID.length; i++) {
                output += 'ID Producto: ' + ArrayID[i] + ', Cantidad: ' + IDCantidad[i] + ', Subtotal: $' + PrecioTotal[i].toFixed(2) + '<br>';
                totalFinal += PrecioTotal[i];
            }

            output += '<strong>Costo Total por Hora: $' + totalFinal.toFixed(2) + '</strong>';
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

        // Función para calcular el Costo Total del Servicio con base en las horas seleccionadas
        function calcularCosto() {
            // Obtener el número de horas desde el input
            const horasAlquiladas = parseInt(document.getElementById('HorasAlquiladas').value);

            if (horasAlquiladas > 0) {
                const costoTotalServicio = totalFinal * horasAlquiladas;
                // Mostrar el costo total del servicio en el div
                document.getElementById('costoTotalServicio').innerHTML = '<strong>Costo Total del Servicio: $' + costoTotalServicio.toFixed(2) + '</strong>';
            } else {
                document.getElementById('costoTotalServicio').innerHTML = '<strong>Por favor, ingresa una cantidad válida de horas.</strong>';
            }
        }
    </script>
</body>


</html>