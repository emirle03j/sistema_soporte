<php

include "conexion.php";

if (!isset($_GET["id"])) {
    die("No se proporcionó un ID válido.");
}

$id = $_GET["id"];

$sql = "SELECT * FROM tecnicos WHERE id = $id";
$stmt = $pdo->query($sql);
$tecnico = $stmt->fetch();

if (isset($_GET["enviar"])) {
    $nombre = $_GET["nombre"];
    $apellido = $_GET["apellido"];
    $cedula = $_GET["cedula"];
    $cargo = $_GET["cargo"];
    $sql = "UPDATE tecnicos SET nombre = '$nombre', apellido = '$apellido', cedula = '$cedula', cargo = '$cargo' WHERE id = $id";
    $pdo->exec($sql);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Técnico</title>
</head>
<body>
    <h2>Editar Técnico</h2>
    <form action="" method="GET">
        Nombre: <input type="text" name="nombre" value="<?php echo $tecnico['nombre']; ?>" required><br><br>
        Apellido: <input type="text" name="apellido" value="<?php echo $tecnico['apellido']; ?>" required><br><br>
        Cédula: <input type="number" name="cedula" value="<?php echo $tecnico['cedula']; ?>" required><br><br>
        Cargo: <input type="text" name="cargo" value="<?php echo $tecnico['cargo']; ?>" required><br><br>
        <input type="submit" name="enviar" value="Guardar Cambios">
    </form>
</body>
</html>
