<h1 class="nombre-pagina">Login</h1>

<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form class="formulario" action="/" method="POST">
    <div class="campo">
        <label for="email">Email:</label>
        <input 
        type="email"
        name="email"
        id="email"
        placeholder="Tu Email"
        >
    </div>

    <div class="campo">
        <label for="password">Password:</label>
        <input 
        type="password"
        name="password"
        id="password"
        placeholder="Tu Password"
        >
    </div>

    <div class="align-right">
        <input type="submit" class="boton" value="Iniciar Sesión">
    </div>
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea Una</a>
    <a href="/olvide-password">¿Olvidaste tu contraseña?</a>
</div>