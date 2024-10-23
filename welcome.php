<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<h1>Bienvenido, <?php echo $_SESSION['email']; ?>!</h1>
<a href="logout.php">Cerrar sesión</a>
