<?php
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
    $nombre = $_POST["nombre"];
    $ubicacion = $_POST["ubicacion"];

    $sql_update = "UPDATE departamentos SET nombre = '$nombre', ubicacion = '$ubicacion' WHERE id = $id";

    if ($conexion->query($sql_update) === TRUE) {
        header("Location: lista_departamento.php");
        exit();
    } else {
        die("Error al actualizar: " . $conexion->error);
    }
}

?>

<?php include "header.php"; ?>

<div class="bg-white p-12 rounded-xl w-3/4 mx-auto">
    <h2 class="text-center uppercase text-xl font-bold mb-4">Editar Departamento</h2>
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
