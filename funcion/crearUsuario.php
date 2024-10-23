<?php
session_start();
include 'conexion.php';

$contadorErrores = 0;

$_SESSION['tituloMensaje'] = "";
$_SESSION['mensaje'] = "";
$_SESSION['icono'] = "";
$_SESSION['posision'] = "";

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
        $_SESSION['tituloMensaje'] = "Campos vacíos!";
        $_SESSION['mensaje'] = "Verifica que el formulario esté completo";
        $_SESSION['icono'] = "warning";
        $_SESSION['posision'] = "center";
        $contadorErrores++;
    }

    // Comprobar si el email ya existe
    $checkEmailQuery = "SELECT * FROM clientes WHERE email='$email'";
    $result = $conn->query($checkEmailQuery);
    if ($result->num_rows > 0) {
        $_SESSION['tituloMensaje'] = "Email ya existente!";
        $_SESSION['mensaje'] = "Prueba con otro correo electrónico.";
        $_SESSION['icono'] = "warning";
        $_SESSION['posision'] = "center";
        $contadorErrores++;
    }

    // Comprobar si el DNI ya existe
    $checkDNIQuery = "SELECT * FROM clientes WHERE dni='$dni'";
    $resultDNI = $conn->query($checkDNIQuery);
    if ($resultDNI->num_rows > 0) {
        $_SESSION['tituloMensaje'] = "DNI ya registrado!";
        $_SESSION['mensaje'] = "Intenta con otro DNI";
        $_SESSION['icono'] = "warning";
        $_SESSION['posision'] = "center";
        $contadorErrores++;
    }

    // Si no hay errores, proceder a la inserción
    if ($contadorErrores === 0) {
        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        // Consulta SQL para insertar datos
        $sql = "INSERT INTO clientes (nombre_cliente, telefono, email, direccion, dni, password) VALUES ('$nombre', '$telefono', '$email', '$direccion', '$dni', '$passwordHash')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['tituloMensaje'] = "Usuario creado!";
            $_SESSION['mensaje'] = "Bienvenido " . $direccion;
            $_SESSION['icono'] = "success";
            $_SESSION['posision'] = "top-end";
            $_SESSION['emailcliente'] = $email;
            header("Location: /index.php");
        } else {
            $_SESSION['tituloMensaje'] = "Oops!";
            $_SESSION['mensaje'] = "Ha ocurrido un error dentro del sistema.";
            $_SESSION['icono'] = "error";
            $_SESSION['posision'] = "center";
            header("Location: /createUser.php");
        }
    } else {
        header("Location: /createUser.php");
    }
}

// Cerrar conexión
$conn->close();