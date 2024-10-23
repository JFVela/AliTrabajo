<?php
session_start();

// Destruir todas las sesiones
session_unset();
session_destroy();

header("Location: loginCliente.php");
exit();
