<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "audio_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

session_start();

$correoCliente = $_SESSION['emailcliente'];

$query = "SELECT 
                c.id_cotizacion, 
                cat.nombre_categoria, 
                p.nombre AS nombre_producto, 
                cd.cantidad, 
                p.precio_hr AS horas_servicio, 
                cd.horas_alquiler, 
                cd.subtotal
            FROM 
                cotizaciones AS c
            INNER JOIN 
                clientes AS cl ON c.id_cliente = cl.id_cliente
            INNER JOIN 
                cotizacion_detalles AS cd ON c.id_cotizacion = cd.id_cotizacion
            INNER JOIN 
                productos AS p ON cd.id_producto = p.id_producto
            INNER JOIN 
                categorias AS cat ON p.id_categoria = cat.id_categoria
            WHERE 
                cl.email = '$correoCliente'";
$result = $conn->query($query);

$cotizaciones = [];
while ($row = $result->fetch_assoc()) {
    $cotizaciones[] = $row;
}

header('Content-Type: application/json');
echo json_encode(['data' => $cotizaciones]);
$conn->close();
?>
