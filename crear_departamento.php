<?php
include "conexion.php";

if (isset($_GET['enviar'])) {
    $nombre = $_GET['nombre'];
    $ubicacion = $_GET['ubicacion'];

    $sql_insertar = "INSERT INTO departamentos (nombre, ubicacion) 
    VALUES ('$nombre', '$ubicacion')";

    if ($conexion->query($sql_insertar) === TRUE) {
        header("Location: lista_departamento.php");
    } else {
        echo "Error al agregar: " . $conexion->error . "<br><br>";
    }
}
$resultado = $conexion->query("SELECT * FROM departamentos ORDER BY id DESC");
?>
<?php include "header.php"; ?>

<div class="bg-white p-12 rounded-xl w-3/4 mx-auto">
    <h2 class="text-center uppercase text-xl font-bold mb-4">Crear Departamento</h2>
    <form action="" method="GET">
    <div class="flex gap-4">
        <div class="flex flex-col   mb-4 w-1/2">
            <label class="text-sm font-bold">Nombre</label>
            <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="nombre" required>
        </div>  
        <div class="flex flex-col mb-4 w-1/2">
            <label class="text-sm font-bold">Ubicacion</label>
            <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="ubicacion" required>
        </div>
    </div>
    <div class="flex flex-col gap-4 mt-4">
        <button type="submit" name="enviar" value="Guardar Departamento" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Guardar Departamento</button>
        <a href="lista_departamento.php" class="text-center bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold">Cancelar</a>
    </div>
    </form>
    </div>
    <?php include "footer.html"; ?>
