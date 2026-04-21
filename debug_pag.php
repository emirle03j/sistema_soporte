<?php
include "conexion.php";
$busqueda = '';
$filtro_tecnico = '';
$items_por_pagina = 7;

$where_clauses = [];
$where = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$total_registros = $conexion->query("SELECT COUNT(*) as total FROM soportes 
                                     LEFT JOIN tecnicos ON soportes.id_tecnico = tecnicos.id 
                                     LEFT JOIN departamentos ON soportes.id_departamento = departamentos.id
                                     $where")->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $items_por_pagina);

echo "Total Registros: $total_registros\n";
echo "Total Paginas: $total_paginas\n";
?>
