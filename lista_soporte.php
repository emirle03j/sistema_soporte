<?php
include "conexion.php";
include "auth_check.php";

$busqueda = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : '';
$filtro_tecnico = isset($_GET['tecnico']) ? $conexion->real_escape_string($_GET['tecnico']) : '';

$items_por_pagina = 7;
$p_actual = isset($_GET['p']) ? (int)$_GET['p'] : (isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1);
if ($p_actual < 1) $p_actual = 1;
$offset = ($p_actual - 1) * $items_por_pagina;

// Construir cláusulas WHERE
$where_clauses = [];
if ($busqueda != '') {
    $where_clauses[] = "(soportes.asunto LIKE '%$busqueda%' 
                         OR soportes.descripcion LIKE '%$busqueda%'
                         OR tecnicos.nombre LIKE '%$busqueda%'
                         OR tecnicos.apellido LIKE '%$busqueda%'
                         OR departamentos.nombre LIKE '%$busqueda%'
                         OR soportes.fecha_soporte LIKE '%$busqueda%')";
}
if (!empty($filtro_tecnico)) {
    $where_clauses[] = "soportes.id_tecnico = '$filtro_tecnico'";
}

$where = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Obtener total para paginación
$total_registros = $conexion->query("SELECT COUNT(*) as total FROM soportes 
                                     LEFT JOIN tecnicos ON soportes.id_tecnico = tecnicos.id 
                                     LEFT JOIN departamentos ON soportes.id_departamento = departamentos.id
                                     $where")->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $items_por_pagina);

// Consulta principal
$sql = "SELECT soportes.*, tecnicos.nombre AS tecnico_nombre, departamentos.nombre AS depto_nombre 
        FROM soportes 
        LEFT JOIN tecnicos ON soportes.id_tecnico = tecnicos.id 
        LEFT JOIN departamentos ON soportes.id_departamento = departamentos.id
        $where
        ORDER BY soportes.id DESC
        LIMIT $items_por_pagina OFFSET $offset";
$resultado = $conexion->query($sql);

// Función interna para renderizar la paginación (para evitar duplicidad)
function renderPagination($p_actual, $total_paginas, $busqueda, $filtro_tecnico) {
    if ($total_paginas <= 1) return '';
    ob_start(); ?>
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 p-4 bg-slate-50/50 border-t border-slate-100">
        <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">
            Página <?php echo $p_actual; ?> de <?php echo $total_paginas; ?>
        </div>
        
        <div class="flex items-center gap-2">
            <?php if ($p_actual > 1): ?>
                <a href="?p=<?php echo $p_actual - 1; ?>&q=<?php echo urlencode($busqueda); ?>&tecnico=<?php echo urlencode($filtro_tecnico); ?>" 
                   class="p-2 bg-white border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 px-3 font-black text-[10px] uppercase page-link shadow-sm" 
                   data-page="<?php echo $p_actual - 1; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Anterior
                </a>
            <?php endif; ?>

            <div class="flex gap-1">
                <?php 
                $rango = 1;
                for ($i = 1; $i <= $total_paginas; $i++): 
                    if ($i == 1 || $i == $total_paginas || ($i >= $p_actual - $rango && $i <= $p_actual + $rango)):
                ?>
                    <a href="?p=<?php echo $i; ?>&q=<?php echo urlencode($busqueda); ?>&tecnico=<?php echo urlencode($filtro_tecnico); ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded-lg font-black text-xs transition-all page-link <?php echo $i == $p_actual ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'; ?>"
                       data-page="<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php 
                    elseif ($i == $p_actual - $rango - 1 || $i == $p_actual + $rango + 1):
                        echo "<span class='flex items-center text-slate-400 px-1 text-xs font-bold'>...</span>";
                    endif;
                endfor; 
                ?>
            </div>

            <?php if ($p_actual < $total_paginas): ?>
                <a href="?p=<?php echo $p_actual + 1; ?>&q=<?php echo urlencode($busqueda); ?>&tecnico=<?php echo urlencode($filtro_tecnico); ?>" 
                   class="p-2 bg-white border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 px-3 font-black text-[10px] uppercase page-link shadow-sm"
                   data-page="<?php echo $p_actual + 1; ?>">
                    Siguiente
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php return ob_get_clean();
}

// Manejo de AJAX
if (isset($_GET['ajax'])) {
    include "lista_soporte_rows.php";
    echo "<!-- PAGINATION_SPLIT -->";
    echo renderPagination($p_actual, $total_paginas, $busqueda, $filtro_tecnico);
    exit;
}

include "header.php";
$lista_tecnicos = $conexion->query("SELECT id, nombre, apellido FROM tecnicos ORDER BY nombre ASC");
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Lista de Soportes</h2>
        <p class="text-slate-500 text-sm font-medium">Gestiona y consulta los tickets de soporte</p>
    </div>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <div class="relative flex-1 md:flex-none">
            <input type="text" id="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar soportes..." class="pl-10 pr-4 py-2.5 border border-slate-200 bg-white rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64 transition-all shadow-sm">
            <svg class="absolute left-3 top-3 h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        
        <select id="filtro-tecnico" class="px-4 py-2.5 border border-slate-200 bg-white rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-600 shadow-sm">
            <option value="">Todos los Técnicos</option>
            <?php while($t = $lista_tecnicos->fetch_assoc()): ?>
                <option value="<?php echo $t['id']; ?>" <?php echo $filtro_tecnico == $t['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($t['nombre'] . ' ' . $t['apellido']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <a href="crear_soporte.php" class="whitespace-nowrap bg-blue-600 text-white px-6 py-2.5 rounded-2xl uppercase font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 hover:scale-105">Crear Soporte</a>
    </div>
</div>

<div id="contenedor-tabla-completo">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200 uppercase text-slate-500 text-[10px] font-black tracking-widest">
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
            <tbody id="tabla-soportes" class="divide-y divide-slate-100">
                <?php include "lista_soporte_rows.php"; ?>
            </tbody>
        </table>

    <div id="contenedor-paginacion">
        <?php echo renderPagination($p_actual, $total_paginas, $busqueda, $filtro_tecnico); ?>
    </div>
    </div>
</div>

<?php include "footer.html"; ?>
<script src="confirmar.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('busqueda');
    const tecnicoSelect = document.getElementById('filtro-tecnico');
    const tableBody = document.getElementById('tabla-soportes');
    const paginationContainer = document.getElementById('contenedor-paginacion');

    let currentUrlParams = new URLSearchParams(window.location.search);

    function actualizarTabla(pagina = 1) {
        const busqueda = searchInput.value;
        const tecnico = tecnicoSelect.value;
        
        const url = `lista_soporte.php?ajax=1&p=${pagina}&q=${encodeURIComponent(busqueda)}&tecnico=${encodeURIComponent(tecnico)}`;
        
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const parts = data.split('<!-- PAGINATION_SPLIT -->');
                tableBody.innerHTML = parts[0];
                paginationContainer.innerHTML = parts[1];
                
                // Actualizar URL sin recargar para mantener consistencia
                const newUrl = `?p=${pagina}&q=${encodeURIComponent(busqueda)}&tecnico=${encodeURIComponent(tecnico)}`;
                window.history.pushState({path:newUrl},'',newUrl);
                
                vincularPaginacion();
            })
            .catch(error => console.error('Error:', error));
    }

    function vincularPaginacion() {
        document.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                actualizarTabla(page);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    }

    searchInput.addEventListener('input', () => actualizarTabla(1));
    tecnicoSelect.addEventListener('change', () => actualizarTabla(1));
    
    vincularPaginacion();
});
</script>
