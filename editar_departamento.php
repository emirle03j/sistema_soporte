<?php
include "header.php";
include "conexion.php";

if (!isset($_GET["id"])) {
    die("No se proporcionó un ID válido.");
};

$id = $_GET["id"];

$resultado = $conexion->query("SELECT * FROM departamentos WHERE id = $id");
$departamento = $resultado->fetch_assoc();

if (!$departamento) {
    die("Departamento no encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conexion->real_escape_string($_POST["nombre"]);
    $ubicacion = $conexion->real_escape_string($_POST["ubicacion"]);

    // Verificar si el departamento ya existe en otro registro
    $verificar = $conexion->query("SELECT id FROM departamentos WHERE nombre = '$nombre' AND id != $id");
    
    if ($verificar->num_rows > 0) {
        $error_depto = "Error: El departamento <strong>$nombre</strong> ya se encuentra registrado.";
    } else {
        $sql_update = "UPDATE departamentos SET nombre = '$nombre', ubicacion = '$ubicacion' WHERE id = $id";

        if ($conexion->query($sql_update) === TRUE) {
            header("Location: lista_departamento.php");
            exit();
        } else {
            $error_depto = "Error al actualizar: " . $conexion->error;
        }
    }
}

?>



<div class="bg-white p-12 rounded-xl w-3/4 mx-auto shadow-sm border border-slate-100">
    <h2 class="text-center uppercase text-xl font-bold mb-6 text-slate-800">Editar Departamento</h2>
    
    <?php if(isset($error_depto)): ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <?php echo $error_depto; ?>
        </div>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="flex gap-4">
            <div class="flex flex-col mb-4 w-1/2">
                <label class="text-sm font-bold">Nombre</label>
                <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="nombre" value="<?php echo htmlspecialchars($departamento['nombre']); ?>" required>
            </div>
            <div class="flex flex-col mb-4 w-1/2">
                <label class="text-sm font-bold">Ubicación</label>
                <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="ubicacion" value="<?php echo htmlspecialchars($departamento['ubicacion']); ?>" required>
            </div>
        </div>
        <div class="flex flex-col mb-4 justify-center">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Guardar Cambios</button>
            <a href="lista_departamento.php" class="text-center mt-4 bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold">Cancelar</a>
        </div>
    </form>
</div>

<?php include "footer.html"; ?>
