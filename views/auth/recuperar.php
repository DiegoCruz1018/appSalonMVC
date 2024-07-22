<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<?php //Si el usuario esta vacio o el token no es valido, no le mostramos el formulario
    if($error) return; 
?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input 
        type="password"
        id="password"
        name="password"
        placeholder="Tu Nuevo Password"
        >
    </div>

    <div class="align-right">
        <input type="submit" class="boton" value="Guardar Nuevo password">
    </div>

    <div class="acciones">
        <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
        <a href="/crear-cuenta">¿Aún no tienes cuenta? Crea Una</a>
    </div>
</form>