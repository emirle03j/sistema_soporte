<?php
include "header.php";
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
    $id = $conexion->real_escape_string($_POST["id"]);
    $asunto = $conexion->real_escape_string($_POST["asunto"]);
    $descripcion = $conexion->real_escape_string($_POST["descripcion"]);
    $id_tecnico = $conexion->real_escape_string($_POST["id_tecnico"]);
    $id_departamento = $conexion->real_escape_string($_POST["id_departamento"]);
    $pc_descripcion = $conexion->real_escape_string($_POST["pc_descripcion"]);
    $estado = $conexion->real_escape_string($_POST["estado"]);

    // Verificar duplicados en otros registros
    $verificar = $conexion->query("SELECT id FROM soportes 
                                   WHERE asunto = '$asunto' 
                                   AND id_departamento = '$id_departamento' 
                                   AND descripcion = '$descripcion'
                                   AND id != $id");
    
    if ($verificar->num_rows > 0) {
        $error_soporte = "Atención: Ya existe otro soporte con los mismos datos para este departamento.";
    } else {
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
            $error_soporte = "Error al actualizar: " . $conexion->error;
        }
    }
}


$resultado_tecnico = $conexion->query("SELECT * FROM tecnicos ORDER BY id DESC");
$resultado_departamento = $conexion->query("SELECT * FROM departamentos ORDER BY id DESC");

?>
<div class="bg-white p-12 rounded-xl w-3/4 mx-auto shadow-sm border border-slate-100">
    <h2 class="text-center uppercase text-xl font-bold mb-6 text-slate-800">Editar Soporte</h2>
    
    <?php if(isset($error_soporte)): ?>
        <div class="bg-yellow-50 text-yellow-700 p-4 rounded-xl mb-6 text-sm font-bold border border-yellow-200 flex items-center gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <?php echo $error_soporte; ?>
        </div>
    <?php endif; ?>
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

