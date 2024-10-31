<?php
class Conexion
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "audio_system");
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    public function getConexion()
    {
        return $this->conn;
    }
}
