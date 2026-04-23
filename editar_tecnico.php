<?php
include "header.php";
include "conexion.php";

if (isset($_GET['id'])) {
    $id = $conexion->real_escape_string($_GET['id']);
    $resultado = $conexion->query("SELECT * FROM tecnicos WHERE id = '$id'");
    $tecnico = $resultado->fetch_assoc();
    $ref = isset($_GET['ref']) ? $_GET['ref'] : 'lista_tecnico.php';
    
    if (!$tecnico) {
        header("Location: lista_tecnico.php");
        exit();
    }
} else {
    header("Location: lista_tecnico.php");
    exit();
}

if (isset($_POST['enviar'])) {
    $id = $conexion->real_escape_string($_POST['id']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $cedula = $conexion->real_escape_string($_POST['cedula']);
    $cargo = $conexion->real_escape_string($_POST['cargo']);

    // Verificar si la cédula ya existe en otro técnico
    $verificar = $conexion->query("SELECT id FROM tecnicos WHERE cedula = '$cedula' AND id != '$id'");
    
    if ($verificar->num_rows > 0) {
        $error_tecnico = "Error: Ya existe otro técnico registrado con la cédula <strong>$cedula</strong>.";
    } else {
        $sql_update = "UPDATE tecnicos SET 
                       nombre = '$nombre', 
                       apellido = '$apellido', 
                       cedula = '$cedula', 
                       cargo = '$cargo' 
                       WHERE id = '$id'";
        
        if ($conexion->query($sql_update) === TRUE) {
            $ref = isset($_POST['ref']) ? $_POST['ref'] : 'lista_tecnico.php';
            header("Location: " . $ref);
            exit();
        } else {
            $error_tecnico = "Error al actualizar: " . $conexion->error;
        }
    }
    
    // Recargar datos actualizados en caso de error para mantener coherencia en el formulario
    $tecnico = [
        'id' => $id,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'cedula' => $cedula,
        'cargo' => $cargo
    ];
}
?>

<div class="bg-white p-12 rounded-xl w-3/4 mx-auto shadow-sm border border-slate-100">
    <h2 class="text-center uppercase text-xl font-bold mb-6 text-slate-800">Editar Técnico</h2>
    
    <?php if(isset($error_tecnico)): ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <?php echo $error_tecnico; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="hidden" name="id" value="<?php echo $tecnico['id']; ?>">
        <input type="hidden" name="ref" value="<?php echo htmlspecialchars($ref); ?>">
        
        <div class="flex gap-4">
            <div class="flex flex-col mb-4 w-1/2">
                <label class="text-sm font-bold">Nombre</label>
                <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="nombre" value="<?php echo htmlspecialchars($tecnico['nombre']); ?>" required>
            </div>  
            <div class="flex flex-col mb-4 w-1/2">
                <label class="text-sm font-bold">Apellido</label>
                <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="apellido" value="<?php echo htmlspecialchars($tecnico['apellido']); ?>" required>
            </div>
        </div>
        
        <div class="flex flex-col mb-4">
            <label class="text-sm font-bold">Cédula</label>
            <input type="number" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="cedula" value="<?php echo htmlspecialchars($tecnico['cedula']); ?>" required>
        </div>
        
        <div class="flex flex-col mb-4">
            <label class="text-sm font-bold">Cargo</label>
            <select name="cargo" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" required>
                <option value="">Seleccione un cargo</option>
                <option value="tecnico" <?php echo ($tecnico['cargo'] == 'tecnico') ? 'selected' : ''; ?>>Tecnico</option>
                <option value="jefe de departamento" <?php echo ($tecnico['cargo'] == 'jefe de departamento') ? 'selected' : ''; ?>>Jefe de departamento</option>
                <option value="redes" <?php echo ($tecnico['cargo'] == 'redes') ? 'selected' : ''; ?>>Ingeniero de redes</option>
            </select>
        </div>

        <div class="flex flex-col gap-4 mt-6">
            <button type="submit" name="enviar" value="Guardar Cambios" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold hover:bg-blue-600 transition-colors">Guardar Cambios</button>
            <a href="<?php echo htmlspecialchars($ref); ?>" class="text-center bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold hover:bg-slate-300 transition-colors">Cancelar</a>
        </div>
    </form>
</div>

<?php include "footer.html"; ?>
