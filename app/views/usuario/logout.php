<?php
session_start();
unset($_SESSION['emailcliente']);

$_SESSION['tituloMensaje'] = "Se cerró sesión";
$_SESSION['mensaje'] = "Adios! ";
$_SESSION['icono'] = "success";
$_SESSION['posision'] = "top-end";

header("Location: index.php");
exit();