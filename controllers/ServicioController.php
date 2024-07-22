<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController{
    public static function index(Router $router){
        //echo "Desde servicios";

        session_start();
        isAdmin();

        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'servicios' => $servicios,
            'nombre' => $_SESSION['nombre']
        ]);
    }

    public static function crear(Router $router){
        //echo "Desde crear servicios";

        session_start();
        isAdmin();

        $servicio = new Servicio;

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)){
                $resultado = $servicio->guardar();

                if($resultado){
                    header('Location: /servicios');
                }
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router){
        //echo "Desde actualizar servicios";

        session_start();
        isAdmin();

        //debuguear($_GET['id']);

        if(!is_numeric($_GET['id'])) return;

        $id = $_GET['id'];

        $servicio = Servicio::find($id);

        $alertas = [];

        //debuguear($servicio);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);

            //debuguear($_POST);

            $alertas = $servicio->validar();

            //debuguear($alertas);

            if(empty($alertas)){
                $resultado = $servicio->guardar();

                if($resultado){
                    header('Location: /servicios');
                }
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(){

        session_start();
        isAdmin();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //debuguear($_POST);
            $id = $_POST['id'];

            $servicio = Servicio::find($id);

            //debuguear($servicio);

            $servicio->eliminar();

            header('Location: /servicios');
        }
    }
}