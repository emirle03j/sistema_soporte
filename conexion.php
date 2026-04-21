<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$db = "sistema_soporte";

$conexion = new mysqli($servidor, $usuario, $password);

if ($conexion->connect_error) {
    die("Error, conexión fallida: " . $conexion->connect_error);
}

// Crear la base de datos si no existe
$conexion->query("CREATE DATABASE IF NOT EXISTS $db");

// Reconectar con la base de datos seleccionada (esto evita errores de paquetes mal formados)
$conexion->close();
$conexion = new mysqli($servidor, $usuario, $password, $db);

if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}

$sql_tecnicos = "CREATE TABLE IF NOT EXISTS tecnicos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cedula INT(11) NOT NULL,
    cargo VARCHAR(100) NOT NULL
) ENGINE=InnoDB;";
$conexion->query($sql_tecnicos);

$sql_deptos = "CREATE TABLE IF NOT EXISTS departamentos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    ubicacion VARCHAR(300) NOT NULL
) ENGINE=InnoDB;";
$conexion->query($sql_deptos);

$sql_soportes = "CREATE TABLE IF NOT EXISTS soportes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    asunto VARCHAR(255) NOT NULL,
    descripcion TEXT,
    id_tecnico INT(11),
    id_departamento INT(11),
    pc_descripcion VARCHAR(255),
    fecha_soporte DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(50) DEFAULT 'Pendiente',
    CONSTRAINT fk_tecnico FOREIGN KEY (id_tecnico) REFERENCES tecnicos(id) ON DELETE SET NULL,
    CONSTRAINT fk_departamento FOREIGN KEY (id_departamento) REFERENCES departamentos(id) ON DELETE SET NULL
) ENGINE=InnoDB;";

if ($conexion->query($sql_soportes)) {
    $res_estado = $conexion->query("SHOW COLUMNS FROM soportes LIKE 'estado'");
    if ($res_estado->num_rows == 0) {
        $conexion->query("ALTER TABLE soportes ADD COLUMN estado VARCHAR(50) DEFAULT 'Pendiente'");
    }

    $res_fecha = $conexion->query("SHOW COLUMNS FROM soportes LIKE 'fecha_soporte'");
    if ($res_fecha->num_rows == 0) {
        $conexion->query("ALTER TABLE soportes ADD COLUMN fecha_soporte DATETIME DEFAULT CURRENT_TIMESTAMP");
    }
} else {
    die("Error al crear la tabla 'soportes': " . $conexion->error);
}

$sql_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    pregunta_seguridad VARCHAR(255),
    respuesta_seguridad VARCHAR(255)
) ENGINE=InnoDB;";

if ($conexion->query($sql_usuarios)) {
    $res = $conexion->query("SHOW COLUMNS FROM usuarios LIKE 'pregunta_seguridad'");
    if ($res->num_rows == 0) {
        $conexion->query("ALTER TABLE usuarios ADD COLUMN pregunta_seguridad VARCHAR(255), ADD COLUMN respuesta_seguridad VARCHAR(255)");
    }
} else {
    die("Error al crear la tabla 'usuarios': " . $conexion->error);
}
?>