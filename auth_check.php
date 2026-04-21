<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 

$pagina_actual = basename($_SERVER['PHP_SELF']);
$paginas_permitidas = ['login.php', 'registro_usuario.php', 'error_usuario.php', 'usuario_creado.php', 'recuperar.php'];

if (!isset($_SESSION['usuario']) && !in_array($pagina_actual, $paginas_permitidas)) {
    header("Location: login.php");
    exit();
}
?>
