<?php
include "conexion.php";
include "auth_check.php";

// Configuración de búsqueda y paginación
$busqueda = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : '';
$items_por_pagina = 7;
$p_actual = isset($_GET['p']) ? (int)$_GET['p'] : (isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1);
if ($p_actual < 1) $p_actual = 1;
$offset = ($p_actual - 1) * $items_por_pagina;

// Construir cláusula WHERE si hay búsqueda
$where = "";
if ($busqueda != '') {
    $where = "WHERE nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%' OR cedula LIKE '%$busqueda%' OR cargo LIKE '%$busqueda%'";
}

// Obtener total de registros para la paginación
$total_registros = $conexion->query("SELECT COUNT(*) as total FROM tecnicos $where")->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $items_por_pagina);

$resultado = $conexion->query("SELECT * FROM tecnicos $where ORDER BY id DESC LIMIT $items_por_pagina OFFSET $offset");

$current_ui_url = "lista_tecnico.php?" . http_build_query(['p' => $p_actual, 'q' => $busqueda]);

// Función interna para renderizar la paginación
function renderTePagination($p_actual, $total_paginas, $busqueda) {
    if ($total_paginas <= 1) return '';
    ob_start(); ?>
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 p-4 bg-slate-50/50 border-t border-slate-100">
        <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">
            Página <?php echo $p_actual; ?> de <?php echo $total_paginas; ?>
        </div>
        
        <div class="flex items-center gap-2">
            <?php if ($p_actual > 1): ?>
                <a href="?p=<?php echo $p_actual - 1; ?>&q=<?php echo urlencode($busqueda); ?>" 
                   class="p-2 bg-white border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 px-3 font-black text-[10px] uppercase page-link shadow-sm" 
                   data-page="<?php echo $p_actual - 1; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Anterior
                </a>
            <?php endif; ?>

            <div class="flex gap-1">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <a href="?p=<?php echo $i; ?>&q=<?php echo urlencode($busqueda); ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded-lg font-black text-xs transition-all page-link <?php echo $i == $p_actual ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'; ?>"
                       data-page="<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>

            <?php if ($p_actual < $total_paginas): ?>
                <a href="?p=<?php echo $p_actual + 1; ?>&q=<?php echo urlencode($busqueda); ?>" 
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
    include "lista_tecnico_rows.php";
    echo "<!-- PAGINATION_SPLIT -->";
    echo renderTePagination($p_actual, $total_paginas, $busqueda);
    exit;
}

include "header.php";
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Lista de Técnicos</h2>
        <p class="text-slate-500 text-sm font-medium">Gestiona el personal técnico de soporte</p>
    </div>
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <div class="relative flex-1 md:flex-none">
            <input type="text" id="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar técnico..." class="pl-10 pr-4 py-2.5 border border-slate-200 bg-white rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64 transition-all shadow-sm">
            <svg class="absolute left-3 top-3 h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <a href="crear_tecnico.php" class="whitespace-nowrap bg-blue-600 text-white px-6 py-2.5 rounded-2xl uppercase font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 hover:scale-105">Crear Técnico</a>
    </div>
</div>

<div id="contenedor-tabla-completo">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200 uppercase text-slate-500 text-[10px] font-black tracking-widest">
                <tr>
                    <th class="p-4 text-center border-r border-slate-100 w-16">ID</th>
                    <th class="p-4 text-left">Nombre Completo</th>
                    <th class="p-4 text-left">Cédula</th>
                    <th class="p-4 text-left">Cargo</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-tecnicos" class="divide-y divide-slate-100">
                <?php include "lista_tecnico_rows.php"; ?>
            </tbody>
        </table>

        <div id="contenedor-paginacion">
            <?php echo renderTePagination($p_actual, $total_paginas, $busqueda); ?>
        </div>
    </div>
</div>

<?php include "footer.html"; ?>
<script src="confirmar.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('busqueda');
    const tableBody = document.getElementById('tabla-tecnicos');
    const paginationContainer = document.getElementById('contenedor-paginacion');

    window.addEventListener('popstate', function() {
        const params = new URLSearchParams(window.location.search);
        searchInput.value = params.get('q') || '';
        actualizarTabla(params.get('p') || 1, false);
    });

    function actualizarTabla(pagina = 1, push = true) {
        const busqueda = searchInput.value;
        const url = `lista_tecnico.php?ajax=1&p=${pagina}&q=${encodeURIComponent(busqueda)}`;
        
        tableBody.style.opacity = '0.5';
        
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const parts = data.split('<!-- PAGINATION_SPLIT -->');
                tableBody.innerHTML = parts[0];
                paginationContainer.innerHTML = parts[1];
                tableBody.style.opacity = '1';
                
                if (push) {
                    const newUrl = `?p=${pagina}&q=${encodeURIComponent(busqueda)}`;
                    window.history.pushState({p: pagina, q: busqueda}, '', newUrl);
                }
                
                vincularPaginacion();
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.style.opacity = '1';
            });
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
    vincularPaginacion();
});
</script>
