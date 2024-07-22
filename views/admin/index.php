<h1 class="nombre-pagina">Panel de Administración</h1>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar Citas</h2>

<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha: </label>
            <input 
            type="date"
            id="fecha"
            name="fecha"
            value="<?php echo $fecha; ?>"
            >
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0){
        echo "<h2>No Hay Citas en esta fecha</h2>";
    }
?>

<div id="citas-admin">

    <ul class="citas">
        <?php 
            $idCita = 0;

            //debuguear($citas); 
            foreach($citas as $key => $cita):
                
                //El $key es la posición que tiene el registro pero en el arreglo
                //debuguear($key);

                //Sirve para que no se repita el id de la cita y solo se muestre una vez la cita
                if($idCita !== $cita->id):

                    $total = 0; //Iniciara en 0 una sola ves hasta que cambie a la siguiente cita

                    $idCita = $cita->id;
        ?>
                    <li>
                        <p>ID: <span> <?php echo $cita->id; ?> </span></p>
                        <p>Hora: <span> <?php echo $cita->hora; ?> </span></p>
                        <p>Cliente: <span> <?php echo $cita->cliente; ?> </span></p>
                        <p>Email: <span> <?php echo $cita->email; ?> </span></p>
                        <p>Teléfono: <span> <?php echo $cita->telefono; ?> </span></p>

                        <h3>Servicios</h3>

                        <?php 
                        
                            endif; 

                            $total += $cita->precio;

                            //echo $total;
                        ?>

                        <p class="servicio"> <?php echo $cita->servicio . " " . $cita->precio; ?> </p>
                    
                        <?php 
                            $actual = $cita->id; //Nos retorna el id en el que nos encontramos
                            $proximo = $citas[$key + 1]->id ?? 0; //Es el indice en el arreglo en la base de datos [0, 1, 2, 3, ....]

                            // echo "<hr>";
                            // debuguear($actual); //Es un string
                            // echo $actual;
                            // echo "<hr>";
                            // echo $proximo;

                            if(esUltimo($actual, $proximo)){ 
                                //echo "SI es ultimo";
                                ?>

                                <p class="total">Total: <span> <?php echo $total; ?> </span> </p>

                                <form action="/api/eliminar" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                                    
                                    <div class="align-right">
                                        <input type="submit" class="boton-eliminar" value="Eliminar">
                                    </div>
                                </form>

                            <?php } ?>
        <?php endforeach; ?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js' ></script>"
?>