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
        echo "Técnico agregado correctamente.<br><br>";
    } else {
        echo "Error al agregar: " . $conexion->error . "<br><br>";
    }
}
$resultado = $conexion->query("SELECT * FROM tecnicos ORDER BY id DESC");
?>
    <?php include "header.php"; ?>
    <br><hr><br>

    <h2>Lista de Técnicos</h2>
    <a href="crear_tecnico.php">Crear Técnico</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Cédula</th>
            <th>Cargo</th>
            <th>Acciones</th>
        </tr>

        <?php
        if ($resultado->num_rows > 0) {
            $cantidad_filas = $resultado->num_rows;
            for ($i = 0; $i < $cantidad_filas; $i++) {
                $fila = $resultado->fetch_assoc();
                echo "<tr>";
                echo "<td>" . $fila['id'] . "</td>";
                echo "<td>" . $fila['nombre'] . "</td>";
                echo "<td>" . $fila['apellido'] . "</td>";
                echo "<td>" . $fila['cedula'] . "</td>";
                echo "<td>" . $fila['cargo'] . "</td>";
                echo "<td>
                <button type='button' onclick='eliminarTecnico(" . $fila['id'] . ")'>Eliminar</button>
                <a href='editar_tecnico.php?id=" . $fila['id'] . "'>Editar</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay técnicos registrados aún</td></tr>";
        }
        ?>

        
    </table>

 <?php include "footer.html"; ?>
<script>
    function eliminarTecnico(id) {
        if (confirm("¿Está seguro de eliminar el técnico?")) {
            window.location.href = "eliminar_tecnico.php?id=" + id;
        }
    }
</script>
</html>
