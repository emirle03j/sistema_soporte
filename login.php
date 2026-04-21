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
                <div class="relative">
                    <input type="password" id="password" class="border bg-slate-200/20 border-gray-400 rounded-xl p-2 mt-2 w-full pr-10 outline-none focus:ring-2 focus:ring-blue-500 transition-all" name="password" required>
                    <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute right-3 top-5 text-slate-500 hover:text-blue-600 transition-colors">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <script>
            function togglePassword(inputId, iconId) {
                const passwordInput = document.getElementById(inputId);
                const eyeIcon = document.getElementById(iconId);
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
                } else {
                    passwordInput.type = "password";
                    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
                }
            }
            </script>
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