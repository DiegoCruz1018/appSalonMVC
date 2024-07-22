<h1 class="nombre-pagina">Olvide Contraseña</h1>

<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuación</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form class="formulario" action="/olvide-password" method="POST">
    <div class="campo">
        <label for="email">Email:</label>
        <input 
        type="email"
        name="email"
        id="email"
        placeholder="Tu Email"
        >
    </div>

    <div class="align-right">
        <input type="submit" class="boton" value="Enviar Instrucciones">
    </div>
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea Una</a>
</div>