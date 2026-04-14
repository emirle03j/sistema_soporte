<?php
session_start();

include "conexion.php";

if (isset($_POST['enviar'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        header("Location: lista_soporte.php");
    } else {
        header("Location: error_usuario.php");
    }   
} else {
    include "header.php";
    ?>
    <div class="bg-white p-12 rounded-xl w-3/4 mx-auto">
        <h2 class="text-center uppercase text-xl font-bold mb-4">Iniciar sesión</h2>
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
                <button type="submit" name="enviar" value="Iniciar Sesion" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Iniciar Sesion</button>
                <div class="flex justify-between items-center text-sm">
                    <a href="registro_usuario.php" class="text-blue-600 hover:underline">Registrar Usuario</a>
                    <a href="recuperar.php" class="text-slate-500 hover:text-blue-600 transition-colors">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
        </form>
    </div>
    <?php include "footer.html";
}