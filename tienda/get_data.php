<?php
header("Content-Type: application/json");
$host = "localhost";
$dbname = "audio_system"; // Nombre de tu base de datos
$username = "tu_usuario"; // Usuario de la base de datos
$password = "tu_contraseña"; // Contraseña de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obteniendo la tabla solicitada
    $table = isset($_GET['table']) ? $_GET['table'] : '';
    $allowedTables = ["clientes", "productos", "reservas", "ventas"];

    // Validar si la tabla solicitada está permitida
    if (in_array($table, $allowedTables)) {
        // Obtener las columnas de la tabla
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Obtener datos (si existen) de la tabla
        $stmt = $pdo->query("SELECT * FROM $table");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver columnas y datos
        echo json_encode(["columns" => $columns, "data" => $data]);
    } else {
        echo json_encode(["error" => "Tabla no permitida."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Conexión fallida: " . $e->getMessage()]);
}
