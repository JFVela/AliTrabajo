<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];

    $sql = "SELECT * FROM audio_system.clientes where email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['emailcliente'] = $user['email'];
            header("Location: /loginCliente.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        } 
    } else {
        echo "No existe una cuenta con ese correo electrónico.";
    }
}
?>
