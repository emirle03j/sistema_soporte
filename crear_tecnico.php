<?php
include "header.php";
include "conexion.php";

if (isset($_GET['enviar'])) {
    $nombre = $conexion->real_escape_string($_GET['nombre']);
    $apellido = $conexion->real_escape_string($_GET['apellido']);
    $cedula = $conexion->real_escape_string($_GET['cedula']);
    $cargo = $_GET['cargo'];

    // Verificar si la cédula ya existe
    $verificar = $conexion->query("SELECT id FROM tecnicos WHERE cedula = '$cedula'");
    
    if ($verificar->num_rows > 0) {
        $error_tecnico = "Error: Ya existe un técnico registrado con la cédula <strong>$cedula</strong>.";
    } else {
        $sql_insertar = "INSERT INTO tecnicos (nombre, apellido, cedula, cargo) 
        VALUES ('$nombre', '$apellido', '$cedula', '$cargo')";
        
        if ($conexion->query($sql_insertar) === TRUE) {
            header("Location: lista_tecnico.php");
            exit();
        } else {
            $error_tecnico = "Error al agregar: " . $conexion->error;
        }
    }
}
$resultado = $conexion->query("SELECT * FROM tecnicos ORDER BY id DESC");
?>


<div class="bg-white p-12 rounded-xl w-3/4 mx-auto shadow-sm border border-slate-100">
    <h2 class="text-center uppercase text-xl font-bold mb-6 text-slate-800">Crear Técnico</h2>
    
    <?php if(isset($error_tecnico)): ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <?php echo $error_tecnico; ?>
        </div>
    <?php endif; ?>
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