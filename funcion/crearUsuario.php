<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Inicializar variables
$mensajeError = [];
$contadorErrores = 0;

// Comprobar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapar los datos para evitar inyecciones SQL
    $nombre = $conn->real_escape_string($_POST['user_nombre']);
    $telefono = $conn->real_escape_string($_POST['user_telefono']);
    $direccion = $conn->real_escape_string($_POST['user_direccion']);
    $email = $conn->real_escape_string($_POST['user_email']);
    $dni = $conn->real_escape_string($_POST['user_dni']);
    $password = $_POST['user_password']; 

    // Validaciones básicas
    if (empty($nombre) || empty($telefono) || empty($direccion) || empty($email) || empty($dni) || empty($password)) {
        $mensajeError[] = "Error: Todos los campos son obligatorios.";
        $contadorErrores++;
    }

    // Comprobar si el email ya existe
    $checkEmailQuery = "SELECT * FROM clientes WHERE email='$email'";
    $result = $conn->query($checkEmailQuery);
    if ($result->num_rows > 0) {
        $mensajeError[] = "Error: El correo electrónico ya está en uso.";
        $contadorErrores++;
    }

    // Comprobar si el DNI ya existe
    $checkDNIQuery = "SELECT * FROM clientes WHERE dni='$dni'";
    $resultDNI = $conn->query($checkDNIQuery);
    if ($resultDNI->num_rows > 0) {
        $mensajeError[] = "Error: El DNI ya está en uso.";
        $contadorErrores++;
    }

    // Si no hay errores, proceder a la inserción
    if ($contadorErrores === 0) {
        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        // Consulta SQL para insertar datos
        $sql = "INSERT INTO clientes (nombre_cliente, telefono, email, direccion, dni, password) VALUES ('$nombre', '$telefono', '$email', '$direccion', '$dni', '$passwordHash')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
        }
    } else {
        // Devolver errores en formato JSON
        echo json_encode(['success' => false, 'message' => implode("\n", $mensajeError)]);
    }
}

// Cerrar conexión
$conn->close();
?>