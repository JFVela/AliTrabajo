<?php
session_start();
require_once '../models/Usuario.php';

class UsuarioController
{
    private $puerto;

    public function __construct()
    {
        // Obtener el puerto actual
        $this->puerto = $_SERVER['SERVER_PORT'];
    }

    public function crearUsuario($nombre, $telefono, $direccion, $email, $dni, $password)
    {
        $usuarioModel = new Usuario();

        // Validaciones
        if (empty($nombre) || empty($telefono) || empty($direccion) || empty($email) || empty($dni) || empty($password)) {
            $_SESSION['tituloMensaje'] = "Campos vacíos!";
            $_SESSION['mensaje'] = "Verifica que el formulario esté completo";
            $_SESSION['icono'] = "warning";
            $_SESSION['posision'] = "center";
            header("Location: http://localhost:{$this->puerto}/app/views/usuario/createUser.php");
            return;
        }
        if ($usuarioModel->verificarEmail($email)) {
            $_SESSION['tituloMensaje'] = "Email ya existente!";
            $_SESSION['mensaje'] = "Prueba con otro correo electrónico.";
            $_SESSION['icono'] = "warning";
            $_SESSION['posision'] = "center";
            header("Location: http://localhost:{$this->puerto}/app/views/usuario/createUser.php");
            return;
        }
        if ($usuarioModel->verificarDNI($dni)) {
            $_SESSION['tituloMensaje'] = "DNI ya registrado!";
            $_SESSION['mensaje'] = "Intenta con otro DNI";
            $_SESSION['icono'] = "warning";
            $_SESSION['posision'] = "center";
            header("Location: http://localhost:{$this->puerto}/app/views/usuario/createUser.php");
            return;
        }

        // Crear usuario
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        if ($usuarioModel->crearUsuario($nombre, $telefono, $direccion, $email, $dni, $passwordHash)) {
            $_SESSION['tituloMensaje'] = "Usuario creado!";
            $_SESSION['mensaje'] = "Bienvenido, $nombre";
            $_SESSION['icono'] = "success";
            $_SESSION['posision'] = "top-end";
            $_SESSION['emailcliente'] = $email;
            header("Location: http://localhost:{$this->puerto}/app/views/usuario/index.php");
        } else {
            $_SESSION['tituloMensaje'] = "Error";
            $_SESSION['mensaje'] = "Ha ocurrido un error dentro del sistema.";
            $_SESSION['icono'] = "error";
            $_SESSION['posision'] = "center";
            header("Location: http://localhost:{$this->puerto}/app/views/usuario/createUser.php");
        }
    }
}

// Verifica si los datos del formulario fueron enviados a través de POST y llama al método de creación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['user_nombre'];
    $telefono = $_POST['user_telefono'];
    $direccion = $_POST['user_direccion'];
    $email = $_POST['user_email'];
    $dni = $_POST['user_dni'];
    $password = $_POST['user_password'];

    $controller = new UsuarioController();
    $controller->crearUsuario($nombre, $telefono, $direccion, $email, $dni, $password);
}
