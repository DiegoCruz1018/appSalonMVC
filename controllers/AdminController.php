<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function index(Router $router){

        session_start();

        isAdmin();

        //debuguear($_GET);
                                //Si no hay nada genera la fecha del servidor (es decir la fecha del dia de hoy)
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        //debuguear($fecha);

        $fechas = explode('-', $fecha); //Separa el string y genera un arreglo de posiciones

        //debuguear($fecha);

        //debuguear(checkdate($fecha[1], $fecha[2], $fecha[0]));

        if(!checkdate($fechas[1], $fechas[2], $fechas[0])){
            header('Location: /404');
        }

        //$fecha = date('Y-m-d');

        //debuguear($fecha);

        //Consultar la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '$fecha' ";

        //debuguear($consulta);

        $citas = AdminCita::SQL($consulta);

        //debuguear($citas);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}