<?php
require_once '../config/conexion.php';

class ClienteSeeder
{
    private $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function generarClientes($cantidad)
    {
        for ($i = 0; $i < $cantidad; $i++) {
            $nombre = $this->generarNombreAleatorio();
            $telefono = $this->generarTelefonoAleatorio();
            $email = $this->generarEmailAleatorio($nombre);
            $direccion = $this->generarDireccionAleatoria();
            $dni = $this->generarDniAleatorio();
            $password = $this->generarPasswordAleatorio();

            $sql = "INSERT INTO clientes (nombre_cliente, telefono, email, direccion, dni, password)
                    VALUES ('$nombre', '$telefono', '$email', '$direccion', '$dni', '$password')";

            if ($this->conexion->query($sql) === TRUE) {
                echo "Cliente $nombre insertado correctamente.<br>";
            } else {
                echo "Error al insertar el cliente: " . $this->conexion->error . "<br>";
            }
        }
    }

    private function generarTelefonoAleatorio()
    {
        return '9' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    private function generarDireccionAleatoria()
    {
        $letras = chr(rand(65, 90)); // Genera una letra mayúscula aleatoria (A-Z)
        return 'Mz ' . $letras . rand(1, 99);
    }

    private function generarDniAleatorio()
    {
        return str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    private function generarPasswordAleatorio()
    {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < 5; $i++) {
            $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $password;
    }

    private function generarNombreAleatorio()
    {
        $longitud = rand(5, 10); 
        $nombre = '';
        for ($i = 0; $i < $longitud; $i++) {
            $nombre .= chr(rand(97, 122)); 
        }
        $nombre .= ' '; 

        $longitudApellido = rand(5, 10); 
        for ($i = 0; $i < $longitudApellido; $i++) {
            $nombre .= chr(rand(97, 122)); 
        }

        return ucfirst($nombre); 
    }

    private function generarEmailAleatorio($nombre)
    {
        $nombreFormateado = strtolower(str_replace(' ', '', $nombre));
        $dominioAleatorio = '@hotmail.com';
        return $nombreFormateado . $dominioAleatorio;
    }
}

// Crear una instancia y generar
$seeder = new ClienteSeeder();
$seeder->generarClientes(1000);
