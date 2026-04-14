<?php
include "conexion.php";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = $conexion->query("SELECT * FROM soportes WHERE id = $id");
    $soporte = $resultado->fetch_assoc();
    if (!$soporte) {
        die("Soporte no encontrado.");
    }
}

if (isset($_POST["enviar"])) {
    $id = $_POST["id"];
    $asunto = $_POST["asunto"];
    $descripcion = $_POST["descripcion"];
    $id_tecnico = $_POST["id_tecnico"];
    $id_departamento = $_POST["id_departamento"];
    $pc_descripcion = $_POST["pc_descripcion"];
    $estado = $_POST["estado"];
    
    $sql_update = "UPDATE soportes SET 
                   asunto = '$asunto', 
                    descripcion = '$descripcion', 
                    id_tecnico = '$id_tecnico', 
                    id_departamento = '$id_departamento', 
                    pc_descripcion = '$pc_descripcion',
                    estado = '$estado' 
                   WHERE id = $id";
    if ($conexion->query($sql_update) === TRUE) {
        header("Location: lista_soporte.php");
        exit();
    } else {
        echo "Error al actualizar: " . $conexion->error . "<br><br>";
    }
}
include "header.php";

$resultado_tecnico = $conexion->query("SELECT * FROM tecnicos ORDER BY id DESC");
$resultado_departamento = $conexion->query("SELECT * FROM departamentos ORDER BY id DESC");

?>
<div class="bg-white p-12 rounded-xl w-3/4 mx-auto">
        <h2 class="text-center uppercase text-xl font-bold mb-4">Editar Soporte</h2>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $soporte['id']; ?>">
        <div class="flex gap-4">
            <div class="flex flex-col mb-4 w-1/2">
        <label class="text-sm font-bold">Tecnico</label>
        <select class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="id_tecnico" required>
                <option value="">Seleccione un tecnico</option>
                <?php while ($fila = $resultado_tecnico->fetch_assoc()) { ?>
                    <option <?php if ($fila['id'] == $soporte['id_tecnico']) echo "selected"; ?> value="<?php echo $fila['id']; ?>"><?php echo $fila['nombre']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="flex flex-col mb-4 w-1/2">
        <label class="text-sm font-bold">Departamento</label>
        <select class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="id_departamento" required>
                <option value="">Seleccione un departamento</option>
                <?php while ($fila = $resultado_departamento->fetch_assoc()) { ?>
                    <option <?php if ($fila['id'] == $soporte['id_departamento']) echo "selected"; ?> value="<?php echo $fila['id']; ?>"><?php echo $fila['nombre']; ?></option>
                <?php } ?>
            </select>
        </div>
        </div>
        <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">PC Descripción</label>
        <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="pc_descripcion" value="<?php echo $soporte['pc_descripcion']; ?>" required>
        </div>
        <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Asunto</label>
            <select class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="asunto" id="asunto" required>
                <option value="">Seleccione un asunto</option>
                <option value="Soporte tecnico" <?php echo ($soporte['asunto'] == 'Soporte tecnico') ? 'selected' : ''; ?>>Soporte tecnico</option>
                <option value="Soporte de software" <?php echo ($soporte['asunto'] == 'Soporte de software') ? 'selected' : ''; ?>>Soporte de software</option>
                <option value="Soporte de hardware" <?php echo ($soporte['asunto'] == 'Soporte de hardware') ? 'selected' : ''; ?>>Soporte de hardware</option>
                <option value="Soporte de impresora" <?php echo ($soporte['asunto'] == 'Soporte de impresora') ? 'selected' : ''; ?>>Soporte de impresora</option>
            </select>
        </div>
        <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Descripción</label>
        <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="descripcion" value="<?php echo $soporte['descripcion']; ?>" required>
        </div>
        <div class="flex flex-col mb-4">
            <label class="text-sm font-bold">Estado</label>
            <select name="estado" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" required>
                <option value="Pendiente" <?php echo ($soporte['estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                <option value="En Proceso" <?php echo ($soporte['estado'] == 'En Proceso') ? 'selected' : ''; ?>>En Proceso</option>
                <option value="Resuelto" <?php echo ($soporte['estado'] == 'Resuelto') ? 'selected' : ''; ?>>Resuelto</option>
            </select>
        </div>
        <div class="flex flex-col mb-4 justify-center">
            <button type="submit" name="enviar" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Guardar Cambios</button>
            <a href="lista_soporte.php" class="text-center mt-4 bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold">Cancelar</a>
        </div>
    </form>
    </div>
    <?php include "footer.html"; ?>

