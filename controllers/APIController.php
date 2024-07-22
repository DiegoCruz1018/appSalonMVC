<?php

namespace Controllers;

use Model\Cita;
use Model\CitasServicios;
use Model\Servicio;

class APIController{
    public static function index(){
        $servicios = Servicio::all();

        //debuguear($servicios);

        echo json_encode($servicios);
    }

    public static function guardar(){
        /*
        $respuesta = [
            'datos' => $_POST
        ];
        */

        //Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        // $respuesta = [
        //     'cita' => $cita
        // ];

        $id = $resultado['id'];

        //Almacena la cita y el servicio

        //Almacena los Servicios con el ID de la Cita
        $idServicios = explode("," , $_POST['servicios']);

        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];

            $citasServicio = new CitasServicios($args);
            $citasServicio->guardar();
        }

        //Retornamos una respuesta
        // $respuesta = [
        //     'resultado' => $resultado
        // ];

        //Un arreglo asociativo es equivalente a un arreglo en javascript

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        //echo "Eliminando Cita...";

        //debuguear($_POST);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];

            //debuguear($_SERVER);

            //debuguear($id);

            //Buscamos el id con find
            $cita = Cita::find($id);

            //debuguear($cita);

            //Eliminamos la cita
            $cita->eliminar();
                                //Para que nos redireccione a la pagina de donde veniamos
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}