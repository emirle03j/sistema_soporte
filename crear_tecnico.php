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

<div class="bg-white p-12 rounded-xl w-3/4 mx-auto">
    <h2 class="text-center uppercase text-xl font-bold mb-4">Crear Técnico</h2>
    <form action="" method="GET">
    <div class="flex gap-4">
        <div class="flex flex-col   mb-4 w-1/2">
            <label class="text-sm font-bold">Nombre</label>
            <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="nombre" required>
        </div>  
        <div class="flex flex-col mb-4 w-1/2">
            <label class="text-sm font-bold">Apellido</label>
            <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="apellido" required>
        </div>
    </div>
    <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Cédula</label>
        <input type="number" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="cedula" required>
    </div>
    <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Cargo</label>
        <select name="cargo" id="" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" required>
            <option value="">Seleccione un cargo</option>
            <option value="tecnico">Tecnico</option>
            <option value="jefe de departamento">Jefe de departamento</option>
            <option value="redes">Ingeniero de redes</option>
        </select>
            <br><br>
    </div>
    <div class="flex flex-col gap-4">
        <button type="submit" name="enviar" value="Guardar Técnico" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Guardar Técnico</button>
        <a href="lista_tecnico.php" class="text-center mt-4 bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold">Cancelar</a>
    </div>
    </form>
    </div>
    <?php include "footer.html"; ?>