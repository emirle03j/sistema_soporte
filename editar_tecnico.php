<?php
include "conexion.php";

if (!isset($_GET["id"])) {
    die("No se proporcionó un ID válido.");
}

$id = $_GET["id"];

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
        header("Location: lista_tecnico.php");
        exit();
    } else {
        die("Error al actualizar: " . $conexion->error);
    }
}

?>

<?php include "header.php"; ?>

    <div class="bg-white p-12 rounded-xl w-3/4 mx-auto">
        <h2 class="text-center uppercase text-xl font-bold mb-4">Editar Técnico</h2>
        <form action="" method="POST">
        <div class="flex gap-4">
            <div class="flex flex-col mb-4 w-1/2">
        <label class="text-sm font-bold">Nombre</label>
        <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="nombre" value="<?php echo htmlspecialchars($tecnico['nombre']); ?>" required>
        </div>
        <div class="flex flex-col mb-4 w-1/2">
        <label class="text-sm font-bold">Apellido</label>
        <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="apellido" value="<?php echo htmlspecialchars($tecnico['apellido']); ?>"required>
        </div>
        </div>
        <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Cédula</label>
        <input type="number" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="cedula" value="<?php echo $tecnico['cedula']; ?>" required>
        </div>
        <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Cargo</label>
            <select class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="cargo" id="cargo" required>
                <option value="">Seleccione un cargo</option>
                <option value="tecnico" <?php echo ($tecnico['cargo'] == 'tecnico') ? 'selected' : ''; ?>>Tecnico</option>
                <option value="jefe de departamento" <?php echo ($tecnico['cargo'] == 'jefe de departamento') ? 'selected' : ''; ?>>Jefe de departamento</option>
                <option value="redes" <?php echo ($tecnico['cargo'] == 'redes') ? 'selected' : ''; ?>>Ingeniero de redes</option>
            </select>
        </div>
        <div class="flex flex-col mb-4 justify-center">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Guardar Cambios</button>
            <a href="lista_tecnico.php" class="text-center mt-4 bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold">Cancelar</a>
        </div>
    </form>
    </div>
<?php include "footer.html"; ?>