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

// Verificar si se pasó el ID del cliente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de cliente no especificado.");
}

$id_cliente = intval($_GET['id']); // Sanitización básica
$query = "SELECT * FROM clientes WHERE id_cliente = $id_cliente";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc(); // Obtener los datos del cliente
} else {
    die("Cliente no encontrado.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Cliente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .cliente-detalle-container {
            max-width: 600px;
            margin: 2rem auto;
            background-color: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .cliente-detalle-titulo {
            color: #007bff;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .cliente-detalle-lista {
            list-style-type: none;
            padding: 0;
        }

        .cliente-detalle-item {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            padding: 0.75rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cliente-detalle-etiqueta {
            font-weight: bold;
            color: #495057;
        }

        .cliente-detalle-valor {
            color: #6c757d;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .cliente-detalle-boton {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cliente-detalle-boton:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include '../../../includes/headerAdmin.php'; ?>

    <div class="cliente-detalle-container">
        <h2 class="cliente-detalle-titulo">Detalle del Cliente</h2>
        <ul class="cliente-detalle-lista">
            <li class="cliente-detalle-item">
                <span class="cliente-detalle-etiqueta">ID:</span>
                <span class="cliente-detalle-valor"><?= $cliente['id_cliente'] ?></span>
            </li>
            <li class="cliente-detalle-item">
                <span class="cliente-detalle-etiqueta">Nombre:</span>
                <span class="cliente-detalle-valor"><?= $cliente['nombre_cliente'] ?></span>
            </li>
            <li class="cliente-detalle-item">
                <span class="cliente-detalle-etiqueta">Dirección:</span>
                <span class="cliente-detalle-valor"><?= $cliente['direccion'] ?></span>
            </li>
            <li class="cliente-detalle-item">
                <span class="cliente-detalle-etiqueta">Teléfono:</span>
                <span class="cliente-detalle-valor"><?= $cliente['telefono'] ?></span>
            </li>
            <li class="cliente-detalle-item">
                <span class="cliente-detalle-etiqueta">Email:</span>
                <span class="cliente-detalle-valor"><?= $cliente['email'] ?></span>
            </li>
            <li class="cliente-detalle-item">
                <span class="cliente-detalle-etiqueta">DNI:</span>
                <span class="cliente-detalle-valor"><?= $cliente['dni'] ?></span>
            </li>
            <li class="cliente-detalle-item">
                <span class="cliente-detalle-etiqueta">Contraseña (hash):</span>
                <span class="cliente-detalle-valor"><?= $cliente['password'] ?></span>
            </li>
        </ul>
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="listaClientes.php" class="cliente-detalle-boton">Volver</a>
        </div>
    </div>
</body>

</html>