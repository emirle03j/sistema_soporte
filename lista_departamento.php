<?php
include "conexion.php";

$sql = "SELECT * FROM departamentos";
$resultado = $conexion->query($sql);

include "header.php";
?>
<div class="flex justify-between aling-center mb-4">
    <h2 class="text-2xl font-bold">Lista de Departamentos</h2>
    <a href="crear_departamento.php"><button class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Crear Departamento</button></a>
</div>
<table class="w-full border-collapse border border-gray-400 p-4 rounded-xl!">
    <thead>
    <tr class="bg-slate-600 uppercase text-white">
        <th class="p-2 text-center">ID</th>
        <th class="p-2 text-center">Nombre</th>
        <th class="p-2 text-center">Ubicacion</th>
        <th class="p-2 text-center">Acciones</th>
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
                echo "<td class='p-2 text-center font-bold'>" . $fila['nombre'] . "</td>";
                echo "<td class='p-2 text-center'>" . $fila['ubicacion'] . "</td>";
                echo "<td class='p-2 text-center'>
                <a onclick='eliminarDepartamento(" . $fila['id'] . ")' class='bg-red-500 text-white px-2 py-2 rounded-xl text-xs uppercase font-bold'>Eliminar</a>
                <a href='editar_departamento.php?id=" . $fila['id'] . "' class='bg-yellow-500 text-white px-2 py-2 rounded-xl text-xs uppercase font-bold'>Editar</a>
            </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay departamentos registrados aún</td></tr>";
        }
        ?>
        <script>
            function eliminarDepartamento(id) {
                if (confirm("¿Está seguro de eliminar el departamento?")) {
                    window.location.href = "eliminar_departamento.php?id=" + id;
                }
            }
        </script>
    </tbody>
</table>
<?php
include "footer.html";
?>