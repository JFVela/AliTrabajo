<?php
session_start();

// Verificar si ya está logueado
if (isset($_SESSION['email'])) {
    header("Location: welcome.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar credenciales (en este caso, estático solo para ejemplo)
    if ($email === 'user@example.com' && $password === 'password123') {
        $_SESSION['email'] = $email;
        header("Location: welcome.php");
        exit();
    } else {
        echo "Correo o contraseña incorrectos.";
    }
}
?>

<form method="post">
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar sesión</button>
</form>
