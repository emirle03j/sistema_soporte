
<div class="bg-white py-2">
    <div class="flex justify-between container mx-auto items-center"> 
    <a href="index.php"><img class="w-40" src="./public/logo.webp" alt="logo"></a>   
<nav>
    <ul class = "flex gap-2 uppercase font-bold text-slate-400" >
        <?php if (isset($_SESSION['usuario'])) { ?>
              <li>
            <a  class="hover:text-slate-600" href="index.php">Inicio</a></li>
        <li>
            <a class="hover:text-slate-600" href="lista_tecnico.php">Técnicos</a></li>
        <li>
            <a class="hover:text-slate-600" href="lista_departamento.php">Departamentos</a>
        </li>
        <li>
            <a class="hover:text-slate-600" href="lista_soporte.php">Soportes</a>
        </li>
            <li>
                <a class="hover:text-slate-600" onclick="cerrarSesion()" href="#">Cerrar Sesión</a>
            </li>
        <?php } else { ?>
            <li>
                <a class="hover:text-slate-600" href="login.php">Login</a>
            </li>
            <li>
                <a class="hover:text-slate-600" href="registro_usuario.php">Registrarse</a>
            </li>   
        <?php } ?>
        </ul>

</nav>
</div>      
</div>

<script>
    function cerrarSesion() {
        if (confirm("¿Está seguro de que desea cerrar sesión?")) {
            window.location.href = "logout.php";
        }
    }
</script>