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
                    <input type="password" name="nuevo_password" placeholder="••••••••" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Confirmar Contraseña</label>
                    <input type="password" name="confirmar_password" placeholder="••••••••" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all">
                </div>
                <button type="submit" name="cambiar_password" class="w-full bg-green-600 text-white font-bold py-3 rounded-xl hover:bg-green-700 transition-colors shadow-lg shadow-green-100">
                    Cambiar Contraseña
                </button>
            </form>

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

        <div class="mt-8 text-center">
            <a href="login.php" class="text-sm text-slate-400 hover:text-blue-600 font-medium transition-colors flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>

<?php include "footer.html"; ?>
