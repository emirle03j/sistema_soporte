<?php 
$estados_tabs = [
    '' => 'Todos',
    'Pendiente' => 'Pendientes',
    'En Proceso' => 'En Proceso',
    'Resuelto' => 'Resueltos'
];

foreach ($estados_tabs as $val => $label): 
    $isActive = ($filtro_estado === $val);
    $count_sql = "SELECT COUNT(*) as total FROM soportes";
    if ($val !== '') {
        $count_sql .= " WHERE estado = '$val'";
    }
    $count = $conexion->query($count_sql)->fetch_assoc()['total'];
?>
    <button type="button" 
            class="tab-estado flex items-center gap-2 px-5 py-2.5 rounded-2xl font-black text-xs uppercase transition-all duration-300 shadow-sm hover:scale-105 active:scale-95
            <?php echo $isActive ? 'bg-blue-600 text-white shadow-blue-200 shadow-lg' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50 hover:border-blue-300 hover:text-blue-600'; ?>" 
            data-estado="<?php echo $val; ?>">
        <?php echo $label; ?>
        <span class="px-2 py-0.5 rounded-lg text-[10px] transition-colors duration-300 <?php echo $isActive ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500'; ?>">
            <?php echo $count; ?>
        </span>
    </button>
<?php endforeach; ?>
