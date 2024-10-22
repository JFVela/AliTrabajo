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
    $password = $_POST['user_password']; // No se escapa aquí para validación

    // Validaciones
    // 1. Validar nombre (no debe contener números)
    if (preg_match('/\d/', $nombre)) {
        $mensajeError[] = "Error: El nombre no puede contener números.";
        $contadorErrores++;
    }

    // 2. Validar teléfono (debe ser numérico y tener 9 caracteres)
    if (!preg_match('/^\d{9}$/', $telefono)) {
        $mensajeError[] = "Error: El teléfono debe contener solo números y tener 9 caracteres.";
        $contadorErrores++;
    }

    // 3. Comprobar si el email ya existe
    $checkEmailQuery = "SELECT * FROM clientes WHERE email='$email'";
    $result = $conn->query($checkEmailQuery);
    if ($result->num_rows > 0) {
        $mensajeError[] = "Error: El correo electrónico ya está en uso.";
        $contadorErrores++;
    }

    // 4. Comprobar si el DNI ya existe
    $checkDNIQuery = "SELECT * FROM clientes WHERE dni='$dni'";
    $resultDNI = $conn->query($checkDNIQuery);
    if ($resultDNI->num_rows > 0) {
        $mensajeError[] = "Error: El DNI ya está en uso.";
        $contadorErrores++;
    }

    // 5. Validar DNI (debe ser numérico y tener 8 caracteres)
    if (!preg_match('/^\d{8}$/', $dni)) {
        $mensajeError[] = "Error: El DNI debe contener solo números y tener 8 caracteres.";
        $contadorErrores++;
    }

    // 6. Validar contraseña (debe tener tanto letras como números)
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]/', $password)) {
        $mensajeError[] = "Error: La contraseña debe contener tanto letras como números.";
        $contadorErrores++;
    }

    // Si no hay errores, proceder a la inserción
    if ($contadorErrores === 0) {
        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Consulta SQL para insertar datos
        $sql = "INSERT INTO clientes (nombre_cliente, telefono, email, direccion, dni, password) VALUES ('$nombre', '$telefono', '$email', '$direccion', '$dni', '$passwordHash')";

        if ($conn->query($sql) === TRUE) {
            $mensajeError[] = "Usuario creado exitosamente.";
        } else {
            $mensajeError[] = "Error: " . $conn->error;
        }
    }
}

// Cerrar conexión
$conn->close();

// Generar el mensaje final
if ($contadorErrores > 0) {
    // Formatear el mensaje de error
    $finalMessage = "Número de Errores: $contadorErrores\n" . implode("\n", $mensajeError);
} else {
    $finalMessage = "Usuario creado exitosamente.";
}
?>

<script>
    // Mostrar el mensaje en la consola
    console.log(<?php echo json_encode($finalMessage); ?>);
</script>