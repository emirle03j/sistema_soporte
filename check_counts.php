<?php
include 'conexion.php';
$soportes = $conexion->query('SELECT COUNT(*) FROM soportes')->fetch_row()[0];
$tecnicos = $conexion->query('SELECT COUNT(*) FROM tecnicos')->fetch_row()[0];
$deptos = $conexion->query('SELECT COUNT(*) FROM departamentos')->fetch_row()[0];
echo "Soportes: $soportes\nTecnicos: $tecnicos\nDepartamentos: $deptos\n";
?>
