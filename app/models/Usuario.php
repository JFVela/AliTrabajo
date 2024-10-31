<?php
require_once '../../config/conexion.php';

class Usuario {
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion();
    }

    public function crearUsuario($nombre, $telefono, $direccion, $email, $dni, $passwordHash) {
        $sql = "INSERT INTO clientes (nombre_cliente, telefono, email, direccion, dni, password) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $nombre, $telefono, $direccion, $email, $dni, $passwordHash);
        return $stmt->execute();
    }

    public function verificarEmail($email) {
        $sql = "SELECT * FROM clientes WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function verificarDNI($dni) {
        $sql = "SELECT * FROM clientes WHERE dni = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function autenticarUsuario($email, $password) {
        $sql = "SELECT * FROM clientes WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return null;
    }
}
?>
