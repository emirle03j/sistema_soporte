<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 

$pagina_actual = basename($_SERVER['PHP_SELF']);
$paginas_permitidas = ['login.php', 'registro_usuario.php', 'error_usuario.php', 'usuario_creado.php', 'recuperar.php'];

if (!isset($_SESSION['usuario']) && !in_array($pagina_actual, $paginas_permitidas)) {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistema de soporte</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body class="bg-slate-200">
    <?php include "menu.php"; ?>
      <div class="h-screen container mx-auto pt-4">
 