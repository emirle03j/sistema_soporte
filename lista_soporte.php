<?php

include "conexion.php";

$sql = "SELECT soportes.*, tecnicos.nombre AS tecnico_nombre, departamentos.nombre AS depto_nombre 
        FROM soportes 
        LEFT JOIN tecnicos ON soportes.id_tecnico = tecnicos.id 
        LEFT JOIN departamentos ON soportes.id_departamento = departamentos.id";
$resultado = $conexion->query($sql);
?>
<?php include "header.php"; ?>
<div class="flex justify-between aling-center mb-4">
    <h2 class="text-2xl font-bold">Lista de Soportes</h2>
    <a href="crear_soporte.php"><button class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Crear Soporte</button></a>
</div>
 <table class="w-full border-collapse border border-gray-400 p-4 rounded-xl!">
        <thead class="bg-slate-600 uppercase text-white">
        <tr class="p-8">
            <th class="p-2">ID</th>
            <th class="p-2">Asunto</th>
            <th class="p-2">Descripción</th>
            <th class="p-2">Técnico</th>
            <th class="p-2">Departamento</th>
            <th class="p-2">PC Descripción</th>
            <th class="p-2">Fecha Soporte</th>
            <th class="p-2">Acciones</th>
        </tr>
        </thead>
        <tbody class="p-8 bg-white">

        <?php
        if ($resultado->num_rows > 0) {
            $cantidad_filas = $resultado->num_rows;
            for ($i = 0; $i < $cantidad_filas; $i++) {
                $fila = $resultado->fetch_assoc();
                echo "<tr>";
                echo "<td class='p-2 text-center'>" . $fila['id'] . "</td>";
                echo "<td class='p-2 text-center font-bold'>" . $fila['asunto'] . "</td>";
                echo "<td class='p-2 text-center'>" . $fila['descripcion'] . "</td>";
                echo "<td class='p-2 text-center'>" . $fila['tecnico_nombre'] . "</td>";
                echo "<td class='p-2 text-center'>" . $fila['depto_nombre'] . "</td>";
                echo "<td class='p-2 text-center'>" . $fila['pc_descripcion'] . "</td>";
                echo "<td class='p-2 text-center'>" . $fila['fecha_soporte'] . "</td>";
                echo "<td class='p-2 text-center'>
                <button type='button' class='bg-red-500 text-white px-2 py-2 rounded-xl text-xs uppercase font-bold' onclick='eliminar(" . $fila['id'] . ", \"eliminar_soporte.php\", \"¿Está seguro de eliminar el soporte?\")'>Eliminar</button>
                <a href='editar_soporte.php?id=" . $fila['id'] . "' class='bg-yellow-500 text-white px-2 py-2 rounded-xl text-xs uppercase font-bold'>Editar</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8' class='p-4 text-center'>No hay soportes registrados aún</td></tr>";
        }
        ?>

        
    </table>

 <?php include "footer.html"; ?>
<script src="confirmar.js"></script>
