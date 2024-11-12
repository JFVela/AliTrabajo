<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "audio_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT idPago, iconoPago, metodoPago, descripcionPago FROM descripcionpago";
$result = $conn->query($sql);

$metodos_pago = array();
while ($row = $result->fetch_assoc()) {
    $metodos_pago[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($metodos_pago);
?>
