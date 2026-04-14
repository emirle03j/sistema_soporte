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
?>

<div class="container mx-auto w-full px-4">
    <!-- Tarjetas de Estadísticas -->
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

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Soportes por Departamento</h3>
            <canvas id="chartSoportes"></canvas>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Distribución de Técnicos por Cargo</h3>
            <div class="max-w-[300px] mx-auto">
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
                label: 'Cantidad de Soportes',
                data: <?php echo json_encode($data_depto); ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    const ctxTecnicos = document.getElementById('chartTecnicos').getContext('2d');
    new Chart(ctxTecnicos, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($labels_cargo); ?>,
            datasets: [{
                data: <?php echo json_encode($data_cargo); ?>,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '70%'
        }
    });
</script>

<?php include "footer.html"; ?> 