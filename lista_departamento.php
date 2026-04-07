<?php
include "header.php";
?>
<h1>Lista de departamentos</h1>
<a href="crear_departamento.php">Crear departamento</a>
<table >
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Ubicacion</th>
        <th>IMG</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Soporte</td>
            <td>Piso 1</td>
            <td>imagen</td>
            <td>
                <button type="button" onclick="eliminarDepartamento(1)">Eliminar</button>
                <a href="editar_departamento.php?id=1">Editar</a>
            </td>
        </tr>
    </tbody>
</table>
<?php
include "footer.html";
?>