<?php
include "conexion.php";

$busqueda = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : '';
$items_por_pagina = 30;
$pagina_actual = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $items_por_pagina;

$where = "";
if ($busqueda != '') {
    $where = "WHERE nombre LIKE '%$busqueda%' OR ubicacion LIKE '%$busqueda%'";
}
$total_registros = $conexion->query("SELECT COUNT(*) as total FROM departamentos $where")->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $items_por_pagina);

$sql = "SELECT * FROM departamentos $where ORDER BY id DESC LIMIT $items_por_pagina OFFSET $offset";
$resultado = $conexion->query($sql);

include "header.php";
?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Lista de Departamentos</h2>
        <p class="text-slate-500 text-sm">Organización y ubicación de la empresa</p>
    </div>
    <div class="flex gap-2 w-full md:w-auto">
        <form action="" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="q" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar depto..." class="px-4 py-2 border border-slate-200 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64 transition-all">
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl hover:bg-slate-700 transition-colors font-bold">Buscar</button>
            <?php if($busqueda != ''): ?>
                <a href="lista_departamento.php" class="bg-slate-200 text-slate-600 px-4 py-2 rounded-xl hover:bg-slate-300 transition-colors font-bold">Limpiar</a>
            <?php endif; ?>
        </form>
        <a href="crear_departamento.php" class="whitespace-nowrap bg-blue-600 text-white px-4 py-2 rounded-xl uppercase font-bold hover:bg-blue-700 transition-colors shadow-md shadow-blue-200">Crear Depto</a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200 uppercase text-slate-500 text-xs font-bold">
            <tr>
                <th class="p-4 text-center border-r border-slate-100 w-16">ID</th>
                <th class="p-4 text-left">Nombre</th>
                <th class="p-4 text-left">Ubicación</th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr class='hover:bg-slate-50 transition-colors'>";
                    echo "<td class='p-4 text-center text-slate-500 font-medium'>" . $fila['id'] . "</td>";
                    echo "<td class='p-4 font-bold text-slate-800'>" . $fila['nombre'] . "</td>";
                    echo "<td class='p-4 text-slate-600 text-sm'>" . $fila['ubicacion'] . "</td>";
                    echo "<td class='p-4 text-center space-x-2'>
                    <a href='editar_departamento.php?id=" . $fila['id'] . "' class='bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-xs uppercase font-bold hover:bg-yellow-200 transition-colors inline-block'>Editar</a>
                    <button type='button' class='bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs uppercase font-bold hover:bg-red-200 transition-colors' onclick='eliminarDepartamento(" . $fila['id'] . ")'>Borrar</button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='p-12 text-center text-slate-400 italic'>No se encontraron departamentos</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php if ($total_paginas > 1): ?>
<div class="flex justify-center items-center gap-2 mt-8 mb-4">
    <?php if ($pagina_actual > 1): ?>
        <a href="?p=<?php echo $pagina_actual - 1; ?>&q=<?php echo urlencode($busqueda); ?>" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-bold transition-all">Anterior</a>
    <?php endif; ?>

    <div class="flex gap-1">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?p=<?php echo $i; ?>&q=<?php echo urlencode($busqueda); ?>" class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition-all <?php echo $i == $pagina_actual ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

    <?php if ($pagina_actual < $total_paginas): ?>
        <a href="?p=<?php echo  $pagina_actual + 1; ?>&q=<?php echo urlencode($busqueda); ?>" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-bold transition-all">Siguiente</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<script>
    function eliminarDepartamento(id) {
        if (confirm("¿Está seguro de eliminar el departamento?")) {
            window.location.href = "eliminar_departamento.php?id=" + id;
        }
    }
</script>

<?php include "footer.html"; ?>