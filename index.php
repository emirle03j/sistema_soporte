<?php
include "conexion.php";
include "header.php";

$total_deptos = $conexion->query("SELECT COUNT(*) as total FROM departamentos")->fetch_assoc()['total'];
$total_tecnicos = $conexion->query("SELECT COUNT(*) as total FROM tecnicos")->fetch_assoc()['total'];
$total_soportes = $conexion->query("SELECT COUNT(*) as total FROM soportes")->fetch_assoc()['total'];

$destacado_query = $conexion->query("SELECT t.nombre, t.apellido, COUNT(s.id) as total_s 
                                     FROM tecnicos t 
                                     JOIN soportes s ON t.id = s.id_tecnico 
                                     GROUP BY t.id 
                                     ORDER BY total_s DESC LIMIT 1");
$tecnico_destacado = $destacado_query->num_rows > 0 ? $destacado_query->fetch_assoc() : ['nombre' => 'N/A', 'apellido' => ''];


$soportes_depto = $conexion->query("SELECT d.nombre, COUNT(s.id) as total 
                                    FROM departamentos d 
                                    LEFT JOIN soportes s ON d.id = s.id_departamento 
                                    GROUP BY d.id");
$labels_depto = [];
$data_depto = [];
while($row = $soportes_depto->fetch_assoc()){
    $labels_depto[] = $row['nombre'];
    $data_depto[] = $row['total'];
}

$tecnicos_cargo = $conexion->query("SELECT cargo, COUNT(*) as total FROM tecnicos GROUP BY cargo");
$labels_cargo = [];
$data_cargo = [];
while($row = $tecnicos_cargo->fetch_assoc()){
    $labels_cargo[] = $row['cargo'];
    $data_cargo[] = $row['total'];
}


$soportes_estado = $conexion->query("SELECT estado, COUNT(*) as total FROM soportes GROUP BY estado");
$labels_estado = [];
$data_estado = [];
while($row = $soportes_estado->fetch_assoc()){
    $labels_estado[] = $row['estado'];
    $data_estado[] = $row['total'];
}
?>

<div class="container mx-auto w-full px-4 mb-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <h2 class="text-slate-500 text-xs uppercase font-bold tracking-wider mb-2">Total Departamentos</h2>
            <p class="text-3xl font-black text-blue-600"><?php echo $total_deptos; ?></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <h2 class="text-slate-500 text-xs uppercase font-bold tracking-wider mb-2">Total Técnicos</h2>
            <p class="text-3xl font-black text-green-600"><?php echo $total_tecnicos; ?></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <h2 class="text-slate-500 text-xs uppercase font-bold tracking-wider mb-2">Total Soportes</h2>
            <p class="text-3xl font-black text-purple-600"><?php echo $total_soportes; ?></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <h2 class="text-slate-500 text-xs uppercase font-bold tracking-wider mb-2">Técnico Destacado</h2>
            <p class="text-xl font-black text-slate-800"><?php echo $tecnico_destacado['nombre'] . ' ' . $tecnico_destacado['apellido']; ?></p>
        </div>
    </div>  

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-2">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                Soportes por Departamento
            </h3>
            <div class="h-[300px]">
                <canvas id="chartSoportes"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-purple-500 rounded-full"></span>
                Soportes por Estado
            </h3>
            <div class="max-w-[250px] mx-auto">
                <canvas id="chartEstados"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-3">
             <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-green-500 rounded-full"></span>
                Distribución de Técnicos por Cargo
            </h3>
            <div class="h-[200px]">
                <canvas id="chartTecnicos"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxSoportes = document.getElementById('chartSoportes').getContext('2d');
    new Chart(ctxSoportes, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels_depto); ?>,
            datasets: [{
                label: 'Cantidad',
                data: <?php echo json_encode($data_depto); ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderRadius: 12,
                barThickness: 30
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { borderDash: [5, 5] } }, x: { grid: { display: false } } }
        }
    });

    const ctxEstados = document.getElementById('chartEstados').getContext('2d');
    const dataEstados = <?php echo json_encode($data_estado); ?>;
    const labelsEstados = <?php echo json_encode($labels_estado); ?>;
    
    const colorMap = {
        'Pendiente': '#f85555ff', 
        'En Proceso': '#3b82f6', 
        'Resuelto': '#0abe64ff'   
    };
    const colors = labelsEstados.map(label => colorMap[label] || '#cbd5e1');

    new Chart(ctxEstados, {
        type: 'doughnut',
        data: {
            labels: labelsEstados,
            datasets: [{
                data: dataEstados,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            },
            cutout: '65%'
        }
    });

    // Gráfico de Línea/Barras horizontal para Técnicos
    const ctxTecnicos = document.getElementById('chartTecnicos').getContext('2d');
    new Chart(ctxTecnicos, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels_cargo); ?>,
            datasets: [{
                data: <?php echo json_encode($data_cargo); ?>,
                backgroundColor: '#10b981',
                borderRadius: 50,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, grid: { display: false } }, y: { grid: { display: false } } }
        }
    });
</script>

<?php include "footer.html"; ?> 