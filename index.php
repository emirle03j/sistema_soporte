<?php
include "header.php";
include "conexion.php";

$total_deptos = $conexion->query("SELECT COUNT(*) as total FROM departamentos")->fetch_assoc()['total'];
$total_tecnicos = $conexion->query("SELECT COUNT(*) as total FROM tecnicos")->fetch_assoc()['total'];
$total_soportes = $conexion->query("SELECT COUNT(*) as total FROM soportes")->fetch_assoc()['total'];

$destacado_query = $conexion->query("SELECT t.nombre, t.apellido, COUNT(s.id) as total_s 
                                     FROM tecnicos t 
                                     JOIN soportes s ON t.id = s.id_tecnico 
                                     GROUP BY t.id 
                                     ORDER BY total_s DESC LIMIT 1");
$tecnico_destacado = $destacado_query->num_rows > 0 ? $destacado_query->fetch_assoc() : ['nombre' => 'Sin', 'apellido' => 'Asignar'];

$soportes_depto = $conexion->query("SELECT d.nombre, COUNT(s.id) as total 
                                    FROM departamentos d 
                                    LEFT JOIN soportes s ON d.id = s.id_departamento 
                                    GROUP BY d.id
                                    ORDER BY total DESC");
$labels_depto = [];
$data_depto = [];
while($row = $soportes_depto->fetch_assoc()){
    $labels_depto[] = $row['nombre'];
    $data_depto[] = (int)$row['total'];
}

$tecnicos_cargo = $conexion->query("SELECT cargo, COUNT(*) as total FROM tecnicos GROUP BY cargo ORDER BY total DESC");
$labels_cargo = [];
$data_cargo = [];
while($row = $tecnicos_cargo->fetch_assoc()){
    $labels_cargo[] = $row['cargo'];
    $data_cargo[] = (int)$row['total'];
}

$soportes_estado = $conexion->query("SELECT estado, COUNT(*) as total FROM soportes GROUP BY estado");
$labels_estado = [];
$data_estado = [];
while($row = $soportes_estado->fetch_assoc()){
    $labels_estado[] = $row['estado'];
    $data_estado[] = (int)$row['total'];
}
?>

<div class="container mx-auto px-6 py-8">
    <!-- Header Hero Section -->
    <div class="mb-10">
        <h1 class="text-4xl font-black text-slate-800 tracking-tight mb-2">Panel de Control</h1>
        <p class="text-slate-500 font-medium text-lg">Resumen general y estadísticas del sistema de soporte</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Card 1 -->
        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-xl hover:scale-[1.02] transition-all cursor-default group">
            <div class="w-16 h-16 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Departamentos</p>
                <p class="text-4xl font-black text-slate-800 leading-none"><?php echo $total_deptos; ?></p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-xl hover:scale-[1.02] transition-all cursor-default group">
            <div class="w-16 h-16 bg-indigo-50 rounded-3xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Técnicos</p>
                <p class="text-4xl font-black text-slate-800 leading-none"><?php echo $total_tecnicos; ?></p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-xl hover:scale-[1.02] transition-all cursor-default group">
            <div class="w-16 h-16 bg-emerald-50 rounded-3xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total Soportes</p>
                <p class="text-4xl font-black text-slate-800 leading-none"><?php echo $total_soportes; ?></p>
            </div>
        </div>

        <!-- Card 4 Featured -->
        <div class="bg-blue-600 p-7 rounded-[2.5rem] shadow-xl flex items-center gap-3 hover:shadow-2xl hover:scale-[1.05] transition-all cursor-default group relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="w-16 h-16 bg-white/20 rounded-3xl flex items-center justify-center text-yellow-300 shadow-inner backdrop-blur-sm group-hover:scale-110 group-hover:text-yellow-400 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 4h-3V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v1H3a1 1 0 0 0-1 1v3c0 4.42 3.58 8 8 8h4c4.42 0 8-3.58 8-8V5a1 1 0 0 0-1-1zM4 8V6h2v6.1C4.81 10.93 4 9.56 4 8zm16 0c0 1.56-.81 2.93-2 4.1V6h2v2zM10 18v2H8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2h-2v-2h-4z"/>
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-blue-100 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Técnico Destacado</p>
                <p class="text-xl font-black text-white leading-tight drop-shadow-sm"><?php echo htmlspecialchars($tecnico_destacado['nombre'] . ' ' . $tecnico_destacado['apellido']); ?></p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Main Chart: Soportes por Departamento (Horizontal) -->
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col">
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 flex items-center gap-4 mb-2">
                        <span class="w-2.5 h-10 bg-blue-500 rounded-full"></span>
                        Distribución de Carga
                    </h3>
                    <p class="text-slate-500 font-medium">Tickets de soporte generados por departamento</p>
                </div>
            </div>
            <div class="flex-grow min-h-[500px]">
                <canvas id="chartSoportes"></canvas>
            </div>
        </div>

        <div class="flex flex-col gap-8">
            <!-- States Doughnut -->
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col">
                <h3 class="text-2xl font-black text-slate-800 mb-2 flex items-center gap-4">
                    <span class="w-2.5 h-10 bg-emerald-500 rounded-full"></span>
                    Estado de Gestión
                </h3>
                <p class="text-slate-500 font-medium mb-10">Progreso actual de los requerimientos</p>
                <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
                    <div class="w-64 h-64 relative">
                        <canvas id="chartEstados"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-4xl font-black text-slate-800"><?php echo $total_soportes; ?></span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center mt-1">Total<br>Tickets</span>
                        </div>
                    </div>
                    <div id="chart-legend" class="flex flex-col gap-3 w-full lg:w-48">
                        <!-- Custom legend managed by JS -->
                    </div>
                </div>
            </div>

            <!-- Horizontal Bar Chart: Técnicos -->
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col">
                <h3 class="text-2xl font-black text-slate-800 mb-2 flex items-center gap-4">
                    <span class="w-2.5 h-10 bg-indigo-500 rounded-full"></span>
                    Fuerza Técnica
                </h3>
                <p class="text-slate-500 font-medium mb-10">Estructura del equipo por especialidad</p>
                <div class="h-[200px]">
                    <canvas id="chartTecnicos"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Outfit', 'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.weight = '500';

    // Gráfico de Soportes (Horizontal Bar)
    const ctxSoportes = document.getElementById('chartSoportes').getContext('2d');
    const gradBlue = ctxSoportes.createLinearGradient(0, 0, 800, 0);
    gradBlue.addColorStop(0, '#2563eb');
    gradBlue.addColorStop(1, '#60a5fa');

    new Chart(ctxSoportes, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels_depto); ?>,
            datasets: [{
                label: 'Soportes',
                data: <?php echo json_encode($data_depto); ?>,
                backgroundColor: gradBlue,
                hoverBackgroundColor: '#1d4ed8',
                borderRadius: 20,
                borderSkipped: false,
                barThickness: 35
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    cornerRadius: 12,
                    displayColors: false
                }
            },
            scales: {
                x: { 
                    beginAtZero: true, 
                    grid: { color: '#f1f5f9', borderDash: [5, 5] }, 
                    ticks: { stepSize: 1, color: '#64748b', font: { size: 11, weight: 'bold' } } 
                },
                y: { 
                    grid: { display: false }, 
                    ticks: { 
                        color: '#334155', 
                        font: { size: 12, weight: '800' },
                        padding: 15
                    } 
                }
            }
        }
    });

    // Gráfico de Estados (Doughnut)
    const ctxEstados = document.getElementById('chartEstados').getContext('2d');
    const labelsEstados = <?php echo json_encode($labels_estado); ?>;
    const dataEstados = <?php echo json_encode($data_estado); ?>;
    
    const colors = {
        'Pendiente': '#f43f5e', 
        'En Proceso': '#3b82f6', 
        'Resuelto': '#10b981'
    };
    
    new Chart(ctxEstados, {
        type: 'doughnut',
        data: {
            labels: labelsEstados,
            datasets: [{
                data: dataEstados,
                backgroundColor: labelsEstados.map(l => colors[l] || '#94a3b8'),
                borderWidth: 10,
                borderColor: '#ffffff',
                hoverOffset: 10,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '82%'
        }
    });

    // Gráfico de Técnicos (Horizontal Bar)
    const ctxTecnicos = document.getElementById('chartTecnicos').getContext('2d');
    const gradIndigo = ctxTecnicos.createLinearGradient(0, 0, 400, 0);
    gradIndigo.addColorStop(0, '#4f46e5');
    gradIndigo.addColorStop(1, '#818cf8');

    new Chart(ctxTecnicos, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels_cargo); ?>,
            datasets: [{
                data: <?php echo json_encode($data_cargo); ?>,
                backgroundColor: gradIndigo,
                borderRadius: 12,
                barThickness: 24
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { 
                    beginAtZero: true, 
                    grid: { display: false }, 
                    ticks: { 
                        stepSize: 1, 
                        display: true,
                        color: '#64748b',
                        font: { weight: 'bold', size: 10 }
                    } 
                },
                y: { grid: { display: false }, ticks: { color: '#475569', font: { weight: '800', size: 11 } } }
            }
        }
    });

    // Leyenda Dinámica Mejorada
    const legendContainer = document.getElementById('chart-legend');
    labelsEstados.forEach((label, i) => {
        const value = dataEstados[i];
        const color = colors[label] || '#94a3b8';
        const item = document.createElement('div');
        item.className = "flex items-center justify-between p-4 rounded-3xl bg-slate-50/50 border border-slate-100 hover:bg-white hover:shadow-md transition-all";
        item.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-3.5 h-3.5 rounded-full shadow-sm" style="background-color: ${color}"></div>
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">${label}</span>
            </div>
            <span class="text-lg font-black text-slate-800">${value}</span>
        `;
        legendContainer.appendChild(item);
    });
</script>
</script>

<?php include "footer.html"; ?>