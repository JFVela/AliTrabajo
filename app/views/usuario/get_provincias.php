<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "audio_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$idDepa = intval($_GET['idDepa']);
$sql = "SELECT idProv, Provincia FROM provincias WHERE idDepa = $idDepa";
$result = $conn->query($sql);

$provincias = array();
while ($row = $result->fetch_assoc()) {
    $provincias[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($provincias);
?>
