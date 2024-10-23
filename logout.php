<?php
session_start();
unset($_SESSION['emailcliente']);

$_SESSION['tituloMensaje'] = "👋 Sesión cerrada";
$_SESSION['mensaje'] = "¡Hasta luego! 😊 Esperamos verte pronto.";
$_SESSION['icono'] = "success";
$_SESSION['posicion'] = "top-end";

header("Location: loginCliente.php");
exit();
