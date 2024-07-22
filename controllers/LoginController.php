<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    //Pagina principal
    public static function login(Router $router){

        $alertas = [];

        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            //debuguear($usuario);

            //Validamos el email y contraseña
            $alertas = $auth->validarLogin();

            //debuguear($alertas);

            if(empty($alertas)){
                //Comprobar que el usuario exista
                $usuario = Usuario::where('email', $auth->email);

                //debuguear($usuario);

                if($usuario){
                    //Verificar el password
                    //debuguear($usuario);

                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //Autenticar al usuario
                        session_start();
                        
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento
                        //debuguear($usuario->idRol);
                        if($usuario->idRol == '1'){
                            //debuguear('Es admin');

                            $_SESSION['admin'] = $usuario->idRol ?? null;

                            header('Location: /admin');
                        }else{
                            //debuguear('Es usuario');
                            header('Location: /cita');
                        }

                        //debuguear($_SESSION);
                    }
                }else{
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        //Obtenemos las alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    //Pagina para cerrar sesión
    public static function logout(Router $router){
        session_start();

        //debuguear($_SESSION);

        $_SESSION = [];

        //debuguear($_SESSION);

        header('Location: /');
    }

    //Pagina para olvidar contraseña
    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            //debuguear($auth);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                //debuguear($usuario);

                if($usuario && $usuario->confirmado == '1'){
                    //debuguear('Si existe y esta confirmado');
                    //debuguear($usuario);

                    //Generar un token
                    $usuario->crearToken();
                    //debuguear($usuario);

                    $usuario->guardar();

                    //TODO: Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                }else{
                    //debuguear('No existe o no esta confirmado');
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    //Pagina para recuperar contraseña
    public static function recuperar(Router $router){

        $alertas = [];

        $error = false;

        $token = s($_GET['token']);

        //debuguear($token);

        //Buscar usuario por su token
        $usuario = Usuario::where('token', $token);
        //debuguear($usuario);

        //Si el usuario esta vacio
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            //Leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            //debuguear($password);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                //debuguear($usuario);

                //Ponemos como null el password anterior
                $usuario->password = null;

                //debuguear($password);

                $usuario->password = $password->password;
                $usuario->hashPassword();

                //Borramos el token de la base de datos
                $usuario->token = null;

                //debuguear($usuario);

                $resultado = $usuario->guardar();

                if($resultado){
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/recuperar', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    //Pagina para crear cuenta
    public static function crear(Router $router){
       
        //Lo ponemos aquí para que se guarde lo escrito en el input 
        $usuario = new Usuario;

        //Alertas Vacias
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            //Sincronizar va iterando en cada dato que enviamos por post y va a sincronizar
            //el objeto que estaba vacio con los datos nuevos
            $usuario->sincronizar($_POST);

            //Aparece el objeto que se enviara
            //debuguear($usuario);
            $alertas = $usuario->validarNuevaCuenta();

            //debuguear($alertas);

            //Revisar que alertas este vacio
            if(empty($alertas)){
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //Hashear Password
                    $usuario->hashPassword();

                    //Generar un Token único
                    $usuario->crearToken();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    
                    //debuguear($email);

                    $email->enviarConfirmacion();

                    //debuguear($usuario);

                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario, 
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){

        $alertas = [];

        //Obtener token de la URL
        $token = s($_GET['token']);

        //debuguear($token);

        //Obtener al usuario por su token
        $usuario = Usuario::where('token', $token);

        //debuguear($usuario);

        if(empty($usuario)){
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        }else{
            //Modificar a usuario confirmado
            $usuario->confirmado = "1";

            //debuguear($usuario);

            //Eliminar token
            $usuario->token = null;

            //debuguear($usuario);

            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}