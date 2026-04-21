<?php
include "conexion.php";

$error_registro = "";
if (isset($_POST['enviar'])) {
    $usuario = $conexion->real_escape_string($_POST['usuario']);
    $password = $_POST['password'];
    $pregunta_seguridad = $_POST['pregunta_seguridad'];
    $respuesta_seguridad = $_POST['respuesta_seguridad'];

    $verificar = $conexion->query("SELECT id FROM usuarios WHERE usuario = '$usuario'");
    
    if ($verificar->num_rows > 0) {
        $error_registro = "El nombre de usuario ya está en uso. Por favor, elige otro.";
    } else {
        $sql = "INSERT INTO usuarios (usuario, password, pregunta_seguridad, respuesta_seguridad) 
                VALUES ('$usuario', '$password', '$pregunta_seguridad', '$respuesta_seguridad')";
        
        if ($conexion->query($sql)) {
            header("Location: usuario_creado.php");
            exit();
        } else {
            $error_registro = "Error al registrar el usuario: " . $conexion->error;
        }
    }
}
include "header.php";
?>

<div class="bg-white p-12 rounded-3xl w-3/4 mx-auto shadow-xl border border-slate-100">
        <h2 class="text-center uppercase text-2xl font-black text-slate-800 mb-6 italic">Registro de Usuario</h2>
        
        <?php if($error_registro): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <?php echo $error_registro; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="flex flex-col mb-4">
                <label class="text-sm font-bold text-slate-700">Usuario</label>
                <input type="text" class="border bg-white border-slate-200 rounded-xl p-2.5 mt-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all" name="usuario" value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" required>
            </div>
            <div class="flex flex-col mb-4">
                <label class="text-sm font-bold text-slate-700">Contraseña</label>
                <div class="relative">
                    <input type="password" id="password" class="border bg-white border-slate-200 rounded-xl p-2.5 mt-2 w-full pr-10 focus:ring-2 focus:ring-blue-500 outline-none transition-all" name="password" required>
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
            <div class="flex flex-col mb-4">
                <label class="text-sm font-bold text-slate-700">Pregunta de Seguridad</label>
                <select name="pregunta_seguridad" class="border bg-white border-slate-200 rounded-xl p-2.5 mt-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                    <option value="">Selecciona una pregunta...</option>
                    <option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
                    <option value="¿En qué ciudad naciste?">¿En qué ciudad naciste?</option>
                    <option value="¿Cuál es tu color favorito?">¿Cuál es tu color favorito?</option>
                    <option value="¿Cómo se llamaba tu escuela primaria?">¿Cómo se llamaba tu escuela primaria?</option>
                    <option value="¿Cuál es el nombre de tu mejor amigo de la infancia?">¿Cuál es el nombre de tu mejor amigo de la infancia?</option>
                </select>
            </div>
            <div class="flex flex-col mb-6">
                <label class="text-sm font-bold text-slate-700">Respuesta de Seguridad</label>
                <input type="text" class="border bg-white border-slate-200 rounded-xl p-2.5 mt-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all" name="respuesta_seguridad" required>
            </div>
            <div class="flex flex-col gap-4">
                <button type="submit" name="enviar" value="Registrar Usuario" class="bg-blue-500 text-white px-4 py-2 rounded-xl uppercase font-bold">Registrar Usuario</button>
                <a href="lista_soporte.php" class="text-center bg-slate-200 px-4 py-2 rounded-xl uppercase font-bold">Cancelar</a>
            </div>
        </form>
    </div>  
    <?php include "footer.html"; ?>