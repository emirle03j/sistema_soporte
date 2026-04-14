<?php
include "conexion.php";

if (isset($_GET['enviar'])) {
    $id_tecnico = $_GET['id_tecnico'];
    $id_departamento = $_GET['id_departamento'];
    $pc_descripcion = $_GET['pc_descripcion'];
    $asunto = $_GET['asunto'];
    $descripcion = $_GET['descripcion'];
    $estado = $_GET['estado'];

    $sql_insertar = "INSERT INTO soportes (id_tecnico, id_departamento, pc_descripcion, asunto, descripcion, estado) 
    VALUES ('$id_tecnico', '$id_departamento', '$pc_descripcion', '$asunto', '$descripcion', '$estado')";
    
    if ($conexion->query($sql_insertar) === TRUE) {
        header("Location: lista_soporte.php");
    } else {
        echo "Error al agregar: " . $conexion->error . "<br><br>";
    }
}
$resultado_tecnico = $conexion->query("SELECT * FROM tecnicos ORDER BY id DESC");
$resultado_departamento = $conexion->query("SELECT * FROM departamentos ORDER BY id DESC");
?>

<?php include "header.php"; ?>

<div class="bg-white p-12 rounded-xl w-3/4 mx-auto">
    <h2 class="text-center uppercase text-xl font-bold mb-4">Crear Soporte</h2>
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