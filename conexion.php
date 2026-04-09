<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$db = "sistema_soporte";

$conexion = new mysqli($servidor, $usuario, $password, $db);

if ($conexion->connect_error) {
    die("Error, conexión fallida: " . $conexion->connect_error);
}

// 1. Tabla Técnicos
$sql_tecnicos = "CREATE TABLE IF NOT EXISTS tecnicos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cedula INT(11) NOT NULL,
    cargo VARCHAR(100) NOT NULL
) ENGINE=InnoDB;";
$conexion->query($sql_tecnicos);

// 2. Tabla Departamentos
$sql_deptos = "CREATE TABLE IF NOT EXISTS departamentos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    ubicacion VARCHAR(300) NOT NULL
) ENGINE=InnoDB;";
$conexion->query($sql_deptos);

// 3. Tabla Soportes (Sin historial, solo la principal)
$sql_soportes = "CREATE TABLE IF NOT EXISTS soportes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    asunto VARCHAR(255) NOT NULL,
    descripcion TEXT,
    id_tecnico INT(11),
    id_departamento INT(11),
    pc_descripcion VARCHAR(255),
    fecha_soporte DATETIME DEFAULT CURRENT_TIMESTAMP,
    -- Estas líneas crean la unión con las otras tablas
    CONSTRAINT fk_tecnico FOREIGN KEY (id_tecnico) REFERENCES tecnicos(id) ON DELETE SET NULL,
    CONSTRAINT fk_departamento FOREIGN KEY (id_departamento) REFERENCES departamentos(id) ON DELETE SET NULL
) ENGINE=InnoDB;";

if ($conexion->query($sql_soportes)) {
} else {
    die("Error al crear la tabla 'soportes': " . $conexion->error);
}
?>