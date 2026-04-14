<?php
include "conexion.php";

if (isset($_POST['enviar'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $sql = "INSERT INTO usuarios (usuario, password) VALUES ('$usuario', '$password')";
    $conexion->query($sql);

    header("Location: usuario_creado.php");
}
include "header.php";

?>

<div class="bg-white p-12 rounded-xl w-3/4 mx-auto">
        <h2 class="text-center uppercase text-xl font-bold mb-4">Registro de Usuario</h2>
        <form action="" method="POST">
            <div class="flex flex-col mb-4">
                <label class="text-sm font-bold">Usuario</label>
                <input type="text" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="usuario" required>
            </div>
            <div class="flex flex-col mb-4">
                <label class="text-sm font-bold">Contraseña</label>
                <input type="password" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2" name="password" required>
            </div>
            <div class="flex flex-col gap-4">
                <button type="submit" name="enviar" value="Registrar Usuario" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Registrar Usuario</button>
                <a href="lista_soporte.php" class="text-center bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold">Cancelar</a>
            </div>
        </form>
    </div>  
    <?php include "footer.html"; ?>