
<button type='button' class='bg-red-500 text-white px-2 py-2 rounded-xl text-xs uppercase font-bold' onclick='eliminar($fila['id'], "eliminar_tecnico.php", "¿Está seguro de eliminar el técnico?")'>
    Eliminar
</button>
                <a href='editar_tecnico.php?id=" . $fila['id'] . "' class='bg-yellow-500 text-white px-2 py-2 rounded-xl text-xs uppercase font-bold'>Editar</a>
                </td>";