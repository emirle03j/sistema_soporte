<?php
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr class='hover:bg-slate-50 transition-colors'>";
        echo "<td class='p-4 text-center text-slate-500 font-medium'>" . $fila['id'] . "</td>";
        echo "<td class='p-4 font-bold text-slate-800'>" . htmlspecialchars($fila['nombre'] . " " . $fila['apellido']) . "</td>";
        echo "<td class='p-4 text-slate-600 text-sm'>" . htmlspecialchars($fila['cedula']) . "</td>";
        echo "<td class='p-4 text-slate-600 text-sm'><span class='inline-block px-2 py-1 bg-slate-100 rounded text-xs'>" . htmlspecialchars($fila['cargo']) . "</span></td>";
        echo "<td class='p-4 text-center space-x-2'>
        <a href='editar_tecnico.php?id=" . $fila['id'] . "&ref=" . urlencode($current_ui_url) . "' class='bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-xs uppercase font-bold hover:bg-yellow-200 transition-colors inline-block'>Editar</a>
        <button type='button' class='bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs uppercase font-bold hover:bg-red-200 transition-colors' onclick='eliminar(" . $fila['id'] . ", \"eliminar_tecnico.php\", \"¿Está seguro de eliminar el técnico?\", \"" . addslashes($current_ui_url) . "\")'>Borrar</button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' class='p-12 text-center text-slate-400 italic'>No se encontraron técnicos que coincidan con la búsqueda</td></tr>";
}
?>
