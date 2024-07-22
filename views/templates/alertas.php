<?php 
    foreach($alertas as $key => $mensajes):
        //debuguear($mensaje);
        foreach($mensajes as $mensaje):
?>
    <div class="alerta <?php echo $key; ?>">
        <?php echo $mensaje ?>
    </div>
<?php
        endforeach;
    endforeach;
?>