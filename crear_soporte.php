<?php
include "header.php";
include "conexion.php";

if (isset($_GET['enviar'])) {
    $id_tecnico = $conexion->real_escape_string($_GET['id_tecnico']);
    $id_departamento = $conexion->real_escape_string($_GET['id_departamento']);
    $pc_descripcion = $conexion->real_escape_string($_GET['pc_descripcion']);
    $asunto = $conexion->real_escape_string($_GET['asunto']);
    $descripcion = $conexion->real_escape_string($_GET['descripcion']);
    $estado = $conexion->real_escape_string($_GET['estado']);

    // Verificar si existe un soporte idéntico reciente
    $verificar = $conexion->query("SELECT id FROM soportes 
                                   WHERE asunto = '$asunto' 
                                   AND id_departamento = '$id_departamento' 
                                   AND descripcion = '$descripcion'
                                   AND estado = 'Pendiente'");
    
    if ($verificar->num_rows > 0) {
        $error_soporte = "Atención: Ya existe un soporte pendiente con el mismo asunto y descripción para este departamento.";
    } else {
        $sql_insertar = "INSERT INTO soportes (id_tecnico, id_departamento, pc_descripcion, asunto, descripcion, estado) 
        VALUES ('$id_tecnico', '$id_departamento', '$pc_descripcion', '$asunto', '$descripcion', '$estado')";
        
        if ($conexion->query($sql_insertar) === TRUE) {
            header("Location: lista_soporte.php");
            exit();
        } else {
            $error_soporte = "Error al agregar: " . $conexion->error;
        }
    }
}
$resultado_tecnico = $conexion->query("SELECT * FROM tecnicos ORDER BY id DESC");
$resultado_departamento = $conexion->query("SELECT * FROM departamentos ORDER BY id DESC");
?>



<div class="bg-white p-12 rounded-xl w-3/4 mx-auto shadow-sm border border-slate-100">
    <h2 class="text-center uppercase text-xl font-bold mb-6 text-slate-800">Crear Soporte</h2>
    
    <?php if(isset($error_soporte)): ?>
        <div class="bg-yellow-50 text-yellow-700 p-4 rounded-xl mb-6 text-sm font-bold border border-yellow-200 flex items-center gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <?php echo $error_soporte; ?>
        </div>
    <?php endif; ?>
    <form action="" method="GET">
    <div class="flex gap-4">
        <div class="flex flex-col   mb-4 w-1/2">
            <label class="text-sm font-bold">Tecnico</label>
            <select class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="id_tecnico" required>
                <option value="">Seleccione un tecnico</option>
                <?php while ($fila = $resultado_tecnico->fetch_assoc()) { ?>
                    <option value="<?php echo $fila['id']; ?>"><?php echo $fila['nombre']; ?></option>
                <?php } ?>
            </select>
        </div>  
        <div class="flex flex-col mb-4 w-1/2">
            <label class="text-sm font-bold">Departamento</label>
            <select class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="id_departamento" required>
                <option value="">Seleccione un departamento</option>
                <?php while ($fila = $resultado_departamento->fetch_assoc()) { ?>
                    <option value="<?php echo $fila['id']; ?>"><?php echo $fila['nombre']; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">PC Descripción</label>
        <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="pc_descripcion" required>
    </div>
    <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Asunto</label>
        <select class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="asunto" required>
            <option value="">Seleccione un asunto</option>
            <option value="Soporte tecnico">Soporte tecnico</option>
            <option value="Soporte de software">Soporte de software</option>
            <option value="Soporte de hardware">Soporte de hardware</option>
            <option value="Soporte de impresora">Soporte de impresora</option>
            
        </select>
    </div>
    <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Descripción</label>
        <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="descripcion" required>
    </div>
    <div class="flex flex-col mb-4">
        <label class="text-sm font-bold">Estado</label>
        <select name="estado" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" required>
            <option value="Pendiente" selected>Pendiente</option>
            <option value="En Proceso">En Proceso</option>
            <option value="Resuelto">Resuelto</option>
        </select>
    </div>
    <div class="flex flex-col gap-4">
        <button type="submit" name="enviar" value="Guardar Soporte" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Guardar Soporte</button>
        <a href="lista_soporte.php" class="text-center bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold">Cancelar</a>
    </div>
    </form>
    </div>
    <?php include "footer.html"; ?>