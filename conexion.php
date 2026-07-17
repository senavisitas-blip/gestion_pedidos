<?php
$host = "localhost";
$usuario = "root";
$password = "";        // por defecto en XAMPP no tiene contraseña
$basedatos = "gestion_pedidos";
$puerto = 3307;

$conexion = new mysqli($host, $usuario, $password, $basedatos, $puerto);

if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

echo "Conexion exitosa a " . $basedatos;
$conexion->close();

