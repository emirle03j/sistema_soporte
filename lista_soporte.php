<?php
include "conexion.php";

$busqueda = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : '';
$items_por_pagina = 30;
$pagina_actual = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $items_por_pagina;

$where = "";
if ($busqueda != '') {
    $where = "WHERE soportes.asunto LIKE '%$busqueda%' OR soportes.descripcion LIKE '%$busqueda%'";
}

$total_registros = $conexion->query("SELECT COUNT(*) as total FROM soportes $where")->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $items_por_pagina);

$sql = "SELECT soportes.*, tecnicos.nombre AS tecnico_nombre, departamentos.nombre AS depto_nombre 
        FROM soportes 
        LEFT JOIN tecnicos ON soportes.id_tecnico = tecnicos.id 
        LEFT JOIN departamentos ON soportes.id_departamento = departamentos.id
        $where
        ORDER BY soportes.id DESC
        LIMIT $items_por_pagina OFFSET $offset";
$resultado = $conexion->query($sql);
?>
<?php include "header.php"; ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Lista de Soportes</h2>
        <p class="text-slate-500 text-sm">Gestiona y consulta los tickets de soporte</p>
    </div>
    <div class="flex gap-2 w-full md:w-auto">
        <form action="" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="q" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar soporte..." class="px-4 py-2 border border-slate-200 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64 transition-all">
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl hover:bg-slate-700 transition-colors font-bold">Buscar</button>
            <?php if($busqueda != ''): ?>
                <a href="lista_soporte.php" class="bg-slate-200 text-slate-600 px-4 py-2 rounded-xl hover:bg-slate-300 transition-colors font-bold">Limpiar</a>
            <?php endif; ?>
        </form>
        <a href="crear_soporte.php" class="whitespace-nowrap bg-blue-600 text-white px-4 py-2 rounded-xl uppercase font-bold hover:bg-blue-700 transition-colors shadow-md shadow-blue-200">Crear Soporte</a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200 uppercase text-slate-500 text-xs font-bold">
            <tr>
                <th class="p-4 text-center border-r border-slate-100 w-16">ID</th>
                <th class="p-4 text-left">Asunto</th>
                <th class="p-4 text-left">Técnico</th>
                <th class="p-4 text-left">Departamento</th>
                <th class="p-4 text-center">Estado</th>
                <th class="p-4 text-left">Fecha</th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    $estado_class = "";
                    switch($fila['estado']) {
                        case 'Pendiente': $estado_class = "bg-red-100 text-red-700"; break;
                        case 'En Proceso': $estado_class = "bg-blue-100 text-blue-700"; break;
                        case 'Resuelto': $estado_class = "bg-green-100 text-green-700"; break;
                        default: $estado_class = "bg-slate-100 text-slate-700";
                    }

                    echo "<tr class='hover:bg-slate-50 transition-colors'>";
                    echo "<td class='p-4 text-center text-slate-500 font-medium'>" . $fila['id'] . "</td>";
                    echo "<td class='p-4'><div class='font-bold text-slate-800'>" . $fila['asunto'] . "</div><div class='text-xs text-slate-400 truncate w-48'>" . $fila['descripcion'] . "</div></td>";
                    echo "<td class='p-4 text-slate-600 text-sm'>" . ($fila['tecnico_nombre'] ?? '<span class="text-slate-300">No asignado</span>') . "</td>";
                    echo "<td class='p-4 text-slate-600 text-sm'>" . ($fila['depto_nombre'] ?? '<span class="text-slate-300">N/A</span>') . "</td>";
                    echo "<td class='p-4 text-center'><span class='px-3 py-1 rounded-full text-[10px] font-black uppercase " . $estado_class . "'>" . $fila['estado'] . "</span></td>";
                    echo "<td class='p-4 text-slate-500 text-xs'>" . date('d/m/Y H:i', strtotime($fila['fecha_soporte'])) . "</td>";
                    echo "<td class='p-4 text-center space-x-2'>
                    <a href='editar_soporte.php?id=" . $fila['id'] . "' class='bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-xs uppercase font-bold hover:bg-yellow-200 transition-colors inline-block'>Editar</a>
                    <button type='button' class='bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs uppercase font-bold hover:bg-red-200 transition-colors' onclick='eliminar(" . $fila['id'] . ", \"eliminar_soporte.php\", \"¿Está seguro de eliminar el soporte?\")'>Borrar</button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='p-12 text-center text-slate-400 italic'>No se encontraron soportes que coincidan con la búsqueda</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Controles de Paginación -->
<?php if ($total_paginas > 1): ?>
<div class="flex justify-center items-center gap-2 mt-8 mb-4">
    <?php if ($pagina_actual > 1): ?>
        <a href="?p=<?php echo $pagina_actual - 1; ?>&q=<?php echo urlencode($busqueda); ?>" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-bold transition-all">Anterior</a>
    <?php endif; ?>

    <div class="flex gap-1">
        <?php 
        // Lógica para mostrar solo algunas páginas si hay muchas
        $rango = 2;
        for ($i = 1; $i <= $total_paginas; $i++): 
            if ($i == 1 || $i == $total_paginas || ($i >= $pagina_actual - $rango && $i <= $pagina_actual + $rango)):
        ?>
            <a href="?p=<?php echo $i; ?>&q=<?php echo urlencode($busqueda); ?>" class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition-all <?php echo $i == $pagina_actual ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'; ?>">
                <?php echo $i; ?>
            </a>
        <?php 
            elseif ($i == $pagina_actual - $rango - 1 || $i == $pagina_actual + $rango + 1):
                echo "<span class='flex items-center text-slate-400'>...</span>";
            endif;
        endfor; 
        ?>
    </div>

    <?php if ($pagina_actual < $total_paginas): ?>
        <a href="?p=<?php echo $pagina_actual + 1; ?>&q=<?php echo urlencode($busqueda); ?>" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-bold transition-all">Siguiente</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include "footer.html"; ?>
<script src="confirmar.js"></script>
