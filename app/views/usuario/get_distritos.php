<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "audio_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$idProv = intval($_GET['idProv']);
$sql = "SELECT idDist, Distrito FROM distritos WHERE idProv = $idProv";
$result = $conn->query($sql);

$distritos = array();
while ($row = $result->fetch_assoc()) {
    $distritos[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($distritos);
?>
