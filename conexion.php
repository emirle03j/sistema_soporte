<?php
    $servidor = "localhost";
    $usuario = "root";
    $password = "";
    $db = "sistema_soporte";

    $conexion = new mysqli($servidor, $usuario, $password, $db);

    if ($conexion->connect_error) {
        die("Error, conexión fallida: " . $conexion->connect_error);
    }

    /*
    $sql = "CREATE DATABASE IF NOT EXISTS $db";
    if ($conexion->query($sql) === TRUE) {
        echo "Base de datos '$db' verificada/creada correctamente.<br>";
    } else {
        die("Error al crear la base de datos: " . $conexion->error);
    }
    */

$sql = "CREATE TABLE IF NOT EXISTS tecnicos (
    id INT (11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cedula INT (11) NOT NULL,
    cargo VARCHAR (100) NOT NULL
)";

if (!$conexion->query($sql)) {
    die("Error al crear la tabla 'tecnicos': " . $conexion->error);
}

$sql = "CREATE TABLE IF NOT EXISTS departamentos (
    id INT (11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    ubicacion VARCHAR(300) NOT NULL
)";

if (!$conexion->query($sql)) {
    die("Error al crear la tabla 'departamentos': " . $conexion->error);
}



