<?php
include "conexion.php";

$resultado = $conexion->query("SELECT * FROM tecnicos ORDER BY id DESC");
?>
    <?php include "header.php"; ?>
    
<div class="flex justify-between aling-center mb-4">
    <h2 class="text-2xl font-bold">Lista de Técnicos</h2>
    <a href="crear_tecnico.php"><button class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Crear Técnico</button></a>
</div>
    <table class="w-full border-collapse border border-gray-400 p-4 rounded-xl!">
        <thead class="bg-slate-600 uppercase text-white">
        <tr class="p-8">
            <th class="p-2">ID</th>
            <th class="p-2">Nombre Completo</th>
            <th class="p-2">Cédula</th>
            <th class="p-2">Cargo</th>
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
                echo "<td class='p-2 text-center font-bold'>" . $fila['nombre'] . " " . $fila ['apellido'] . "</td>";
                echo "<td class='p-2 text-center'>" . $fila['cedula'] . "</td>";
                echo "<td class='p-2 text-center'>" . $fila['cargo'] . "</td>";
                echo "<td class='p-2 text-center'>
                <button type='button' class='bg-red-500 text-white px-2 py-2 rounded-xl text-xs uppercase font-bold' onclick='eliminarTecnico(" . $fila['id'] . ")'>Eliminar</button>
                <a href='editar_tecnico.php?id=" . $fila['id'] . "' class='bg-yellow-500 text-white px-2 py-2 rounded-xl text-xs uppercase font-bold'>Editar</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay técnicos registrados aún</td></tr>";
        }
        ?>

        
    </table>

 <?php include "footer.html"; ?>
<script>
    function eliminarTecnico(id) {
        if (confirm("¿Está seguro de eliminar el técnico?")) {
            window.location.href = "eliminar_tecnico.php?id=" + id;
        }
    }
</script>
</html>
