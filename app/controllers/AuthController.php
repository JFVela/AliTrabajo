<?php
session_start();
require_once '../models/Usuario.php';

class AuthController
{
    private $puerto;

    public function __construct()
    {
        // Obtener el puerto actual
        $this->puerto = $_SERVER['SERVER_PORT'];
    }

    public function iniciarSesion($email, $password)
    {
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->autenticarUsuario($email, $password);

        if ($usuario) {
            $_SESSION['emailcliente'] = $usuario['email'];
            $_SESSION['tituloMensaje'] = "Inicio de sesión exitoso!";
            $_SESSION['mensaje'] = "Bienvenido, " . $usuario['nombre_cliente'];
            $_SESSION['icono'] = "success";
            $_SESSION['posision'] = "top-end";

            // Redirecciona a la página principal usando el puerto dinámico
            header("Location: http://localhost:{$this->puerto}/app/views/usuario/index.php");
        } else {
            $_SESSION['tituloMensaje'] = "Error";
            $_SESSION['mensaje'] = "Usuario o contraseña incorrectos.";
            $_SESSION['icono'] = "error";
            $_SESSION['posision'] = "center";

            // Redirecciona de nuevo a la página de login usando el puerto dinámico
            header("Location: http://localhost:{$this->puerto}/app/views/usuario/loginCliente.php");
        }
        exit();
    }
    /*
    public function logout()
    {
        session_destroy();
        
        // Redirecciona a la página principal usando el puerto dinámico
        header("Location: http://localhost:{$this->puerto}/app/views/usuario/index.php");
        exit();
    }
    */
}

// Verificar si el formulario de inicio de sesión se envió mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];

    $authController = new AuthController();
    $authController->iniciarSesion($email, $password);
}
