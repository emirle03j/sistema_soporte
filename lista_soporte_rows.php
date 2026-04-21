<?php
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $estado_class = "";
        switch($fila['estado']) {
            case 'Pendiente': $estado_class = "bg-red-100 text-red-700"; break;
            case 'En Proceso': $estado_class = "bg-blue-100 text-blue-700"; break;
            case 'Resuelto': $estado_class = "bg-green-100 text-green-700"; break;
            default: $estado_class = "bg-slate-100 text-slate-700";
        }

        echo "<tr class='hover:bg-slate-50 transition-colors'>";
        echo "<td class='p-4 text-center text-slate-500 font-medium'>" . $fila['id'] . "</td>";
        echo "<td class='p-4'><div class='font-bold text-slate-800'>" . $fila['asunto'] . "</div><div class='text-xs text-slate-400 truncate w-48'>" . $fila['descripcion'] . "</div></td>";
        echo "<td class='p-4 text-slate-600 text-sm'>" . ($fila['tecnico_nombre'] ?? '<span class="text-slate-300">No asignado</span>') . "</td>";
        echo "<td class='p-4 text-slate-600 text-sm'>" . ($fila['depto_nombre'] ?? '<span class="text-slate-300">N/A</span>') . "</td>";
        echo "<td class='p-4 text-center'><span class='px-3 py-1 rounded-full text-[10px] font-black uppercase " . $estado_class . "'>" . $fila['estado'] . "</span></td>";
        echo "<td class='p-4 text-slate-500 text-xs'>" . date('d/m/Y H:i', strtotime($fila['fecha_soporte'])) . "</td>";
        echo "<td class='p-4 text-center space-x-2'>
        <a href='editar_soporte.php?id=" . $fila['id'] . "' class='bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-xs uppercase font-bold hover:bg-yellow-200 transition-colors inline-block'>Editar</a>
        <button type='button' class='bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs uppercase font-bold hover:bg-red-200 transition-colors' onclick='eliminar(" . $fila['id'] . ", \"eliminar_soporte.php\", \"¿Está seguro de eliminar el soporte?\")'>Borrar</button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='p-12 text-center text-slate-400 italic'>No se encontraron soportes que coincidan con la búsqueda</td></tr>";
}
?>
