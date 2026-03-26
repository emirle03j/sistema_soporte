<?php
include "conexion.php";

if (isset($_GET['enviar'])) {
    $nombre = $_GET['nombre'];
    $apellido = $_GET['apellido'];
    $cedula = $_GET['cedula'];
    $cargo = $_GET['cargo'];

    $sql_insertar = "INSERT INTO tecnicos (nombre, apellido, cedula, cargo) 
    VALUES ('$nombre', '$apellido', '$cedula', '$cargo')";
    
    if ($conexion->query($sql_insertar) === TRUE) {
        header("Location: lista_tecnico.php");
    } else {
        echo "Error al agregar: " . $conexion->error . "<br><br>";
    }
}
$resultado = $conexion->query("SELECT * FROM tecnicos ORDER BY id DESC");
?>
<?php include "header.php"; ?>
<form action="" method="GET">
        Nombre: <input type="text" name="nombre" required><br><br>
        Apellido: <input type="text" name="apellido" required><br><br>
        Cédula: <input type="number" name="cedula" required><br><br>
        Cargo: <select name="cargo" id="">
            <option value="">Seleccione un cargo</option>
            <option value="tecnico">Tecnico</option>
            <option value="jefe_de_departamento">Jefe de departamento</option>
            <option value="redes">Ingeniero de redes</option>
        </select>
            <br><br>
        <input type="submit" name="enviar" value="Guardar Técnico">
    </form>
    <?php include "footer.html"; ?>