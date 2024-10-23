<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];

    $sql = "SELECT * FROM audio_system.clientes WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $_SESSION['posision'] = "center";

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['emailcliente'] = $user['email'];
            $_SESSION['tituloMensaje'] = "Inicio de sesión Exitoso!";
            $_SESSION['mensaje'] = "Bienvenido! " . $_SESSION['emailcliente'];
            $_SESSION['icono'] = "success";
            $_SESSION['posision'] = "top-end";
        } else {
            $_SESSION['tituloMensaje'] = "Oops!";
            $_SESSION['mensaje'] = "Contraseña incorrecta.";
            $_SESSION['icono'] = "error";
        }
    } else {
        $_SESSION['tituloMensaje'] = "Error!";
        $_SESSION['mensaje'] = "No existe una cuenta con ese correo electrónico.";
        $_SESSION['icono'] = "warning";
    }
    header("Location: /loginCliente.php");
    exit();
}
