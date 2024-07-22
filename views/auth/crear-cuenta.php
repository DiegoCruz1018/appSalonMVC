<h1 class="nombre-pagina">Crear Cuenta</h1>

<p class="descripcion-pagina">Llena el siguiente formulario para crear un cuenta</p>

<!-- <?php //debuguear($usuario); ?> -->
<!-- <?php //debuguear($alertas); ?> -->

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form class="formulario" action="/crear-cuenta" method="POST">
    <div class="campo">
        <label for="nombre">Nombre: </label>
        <input 
        type="text"
        name="nombre"
        id="nombre"
        placeholder="Tu Nombre"
        value="<?php echo s($usuario->nombre); ?>"
        >
    </div>

    <div class="campo">
        <label for="apellido">Apellido: </label>
        <input 
        type="text"
        name="apellido"
        id="apellido"
        placeholder="Tus Apellido"
        value="<?php echo s($usuario->apellido); ?>"
        >
    </div>

    <div class="campo">
        <label for="telefono">Teléfono: </label>
            <input 
            type="tel"
            name="telefono"
            id="telefono"
            placeholder="Tu Teléfono"
            value="<?php echo s($usuario->telefono); ?>"
            >
    </div>

    <div class="campo">
        <label for="email">Email: </label>
            <input 
            type="email"
            name="email"
            id="email"
            placeholder="Tu Email"
            value="<?php echo s($usuario->email); ?>"
            >
    </div>

    <div class="campo">
        <label for="password">Password: </label>
            <input 
            type="password"
            name="password"
            id="password"
            placeholder="Tu Password"
            >
    </div>

    <div class="align-right">
        <input type="submit" class="boton" value="Crear Cuenta">
    </div>
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide-password">¿Olvidaste tu contraseña?</a>
</div>