<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "audio_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT idDepa, Departamento FROM departamentos";
$result = $conn->query($sql);

$departamentos = array();
while ($row = $result->fetch_assoc()) {
    $departamentos[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($departamentos);
?>
