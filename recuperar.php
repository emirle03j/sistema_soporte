<?php
include "conexion.php";
session_start();

$paso = 1;
$error = "";
$mensaje = "";
$pregunta = "";
$usuario_recuperar = "";

// Paso 1: Verificar el nombre de usuario
if (isset($_POST['verificar_usuario'])) {
    $usuario_recuperar = $conexion->real_escape_string($_POST['usuario']);
    $resultado = $conexion->query("SELECT pregunta_seguridad FROM usuarios WHERE usuario = '$usuario_recuperar'");

    if ($resultado->num_rows > 0) {
        $usuario_data = $resultado->fetch_assoc();
        if (!empty($usuario_data['pregunta_seguridad'])) {
            $_SESSION['usuario_recuperar'] = $usuario_recuperar;
            $pregunta = $usuario_data['pregunta_seguridad'];
            $paso = 2;
        } else {
            $error = "Este usuario no tiene configurada una pregunta de seguridad.";
        }
    } else {
        $error = "El usuario no existe.";
    }
}

// Paso 2: Verificar la respuesta
if (isset($_POST['verificar_respuesta'])) {
    $usuario_recuperar = $_SESSION['usuario_recuperar'];
    $respuesta_ingresada = $conexion->real_escape_string($_POST['respuesta']);
    
    $resultado = $conexion->query("SELECT * FROM usuarios WHERE usuario = '$usuario_recuperar' AND respuesta_seguridad = '$respuesta_ingresada'");
    
    if ($resultado->num_rows > 0) {
        $paso = 3;
    } else {
        $error = "La respuesta es incorrecta.";
        // Recuperar la pregunta de nuevo para mostrarla en el paso 2
        $res_q = $conexion->query("SELECT pregunta_seguridad FROM usuarios WHERE usuario = '$usuario_recuperar'");
        $pregunta = $res_q->fetch_assoc()['pregunta_seguridad'];
        $paso = 2;
    }
}

// Paso 3: Cambiar la contraseña
if (isset($_POST['cambiar_password'])) {
    $usuario_recuperar = $_SESSION['usuario_recuperar'];
    $nuevo_password = $_POST['nuevo_password']; // En un sistema real deberíamos usar password_hash
    $confirmar_password = $_POST['confirmar_password'];

    if ($nuevo_password === $confirmar_password) {
        $sql = "UPDATE usuarios SET password = '$nuevo_password' WHERE usuario = '$usuario_recuperar'";
        if ($conexion->query($sql)) {
            $mensaje = "Tu contraseña ha sido actualizada con éxito.";
            unset($_SESSION['usuario_recuperar']);
            $paso = 4;
        } else {
            $error = "Error al actualizar la contraseña.";
            $paso = 3;
        }
    } else {
        $error = "Las contraseñas no coinciden.";
        $paso = 3;
    }
}

$title = "Recuperar Contraseña";
include "header.php";
?>

<div class="max-w-md mx-auto mt-12 mb-12">
    <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Recuperación</h2>
            <p class="text-slate-500 text-sm mt-1">Sigue los pasos para resetear tu acceso</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-medium border border-red-100">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if($paso == 1): ?>
            <!-- Paso 1: Usuario -->
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nombre de Usuario</label>
                    <input type="text" name="usuario" placeholder="Ingresa tu usuario" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all">
                </div>
                <button type="submit" name="verificar_usuario" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-100">
                    Siguiente Paso
                </button>
            </form>

        <?php elseif($paso == 2): ?>
            <!-- Paso 2: Pregunta -->
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-1">Tu pregunta de seguridad:</label>
                    <p class="text-slate-800 font-bold text-lg mb-4"><?php echo $pregunta; ?></p>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tu Respuesta</label>
                    <input type="text" name="respuesta" placeholder="Escribe tu respuesta aquí" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all">
                </div>
                <button type="submit" name="verificar_respuesta" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-100">
                    Verificar Respuesta
                </button>
            </form>

        <?php elseif($paso == 3): ?>
            <!-- Paso 3: Nueva Contraseña -->
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nueva Contraseña</label>
                    <div class="relative">
                        <input type="password" id="nuevo_password" name="nuevo_password" placeholder="••••••••" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all pr-10">
                        <button type="button" onclick="togglePassword('nuevo_password', 'eye-icon-1')" class="absolute right-3 top-3.5 text-slate-500 hover:text-blue-600 transition-colors">
                            <svg id="eye-icon-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Confirmar Contraseña</label>
                    <div class="relative">
                        <input type="password" id="confirmar_password" name="confirmar_password" placeholder="••••••••" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all pr-10">
                        <button type="button" onclick="togglePassword('confirmar_password', 'eye-icon-2')" class="absolute right-3 top-3.5 text-slate-500 hover:text-blue-600 transition-colors">
                            <svg id="eye-icon-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit" name="cambiar_password" class="w-full bg-green-600 text-white font-bold py-3 rounded-xl hover:bg-green-700 transition-colors shadow-lg shadow-green-100">
                    Cambiar Contraseña
                </button>
            </form>

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

        <?php elseif($paso == 4): ?>
            <!-- Paso 4: Éxito -->
            <div class="text-center space-y-6">
                <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm font-bold border border-green-100">
                    <?php echo $mensaje; ?>
                </div>
                <a href="login.php" class="block w-full bg-slate-800 text-white font-bold py-3 rounded-xl hover:bg-slate-700 transition-colors text-center">
                    Ir al Inicio de sesión
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "footer.html"; ?>
