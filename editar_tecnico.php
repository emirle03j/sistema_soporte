<?php
include "conexion.php";

// 1. Validar que el ID exista al cargar la página
if (!isset($_GET["id"])) {
    die("No se proporcionó un ID válido.");
}

$id = $_GET["id"];

// 2. Obtener los datos actuales del técnico (Usando MySQLi simple)
$resultado = $conexion->query("SELECT * FROM tecnicos WHERE id = $id");
$tecnico = $resultado->fetch_assoc();

if (!$tecnico) { 
    echo "ID buscado: " . $id;
    die(" Técnico no encontrado en la base de datos.");
}

// 3. Procesar la actualización al recibir el POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $cedula = $_POST["cedula"];
    $cargo = $_POST["cargo"];

    // Actualizar los datos con una consulta simple
    $sql_update = "UPDATE tecnicos SET 
                   nombre = '$nombre', 
                   apellido = '$apellido', 
                   cedula = '$cedula', 
                   cargo = '$cargo' 
                   WHERE id = $id";

    if ($conexion->query($sql_update) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        die("Error al actualizar: " . $conexion->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Técnico</title>
</head>
<body>
    <?php include "menu.html"; ?>
    <h2>Editar Técnico</h2>
    <form action="" method="POST">
        Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($tecnico['nombre']); ?>" required><br><br>
        Apellido: <input type="text" name="apellido" value="<?php echo htmlspecialchars($tecnico['apellido']); ?>" required><br><br>
        Cédula: <input type="number" name="cedula" value="<?php echo $tecnico['cedula']; ?>" required><br><br>
        Cargo: <select name="cargo" id="">
            <option value="">Seleccione un cargo</option>
            <option value="jecnico">Tecnico</option>
            <option value="jefe_de_departamento">Jefe de departamento</option>
            <option value="redes">Ingeniero de redes</option>
        </select><br><br>
        
        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>