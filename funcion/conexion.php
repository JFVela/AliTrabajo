<?php
$servername = "localhost"; // Cambia si es necesario
$username = "root"; // Cambia por tu usuario
$password = ""; // Cambia por tu contraseña
$dbname = "audio_system"; // Cambia por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    $message = json_encode(array("status" => "error", "message" => "Error de conexión: " . $conn->connect_error));
} else {
    $message = json_encode(array("status" => "success", "message" => "Conexión exitosa"));
}

// $conn->close();
?>

<script>
    console.log(<?php echo $message; ?>);
</script>
