<?php

namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';

    //Es para iterar sobre todos los registros y los inserta en memoria
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'telefono', 'idRol', 'confirmado', 'token', 'password'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $idRol;
    public $confirmado;
    public $token;
    public $password;

    public function __construct($args=[])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->idRol = $args['idRol'] ?? '2';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    //Mensajes de validación para crear cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'EL nombre es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }
        if(!$this->telefono){
            self::$alertas['error'][] = 'El teléfono es obligatorio';
        }
        if(strlen($this->telefono) != 10){
            self::$alertas['error'][] = 'El teléfono debe contener 10 caracteres';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe de contener 6 o más caracteres';
        }

        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe de contener 6 o más caracteres';
        }

        return self::$alertas;
    }

    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        //debuguear($query);
        $resultado = self::$db->query($query);

        //debuguear($resultado);

        if($resultado->num_rows){
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){
        //Para ver el objeto actual
        //debuguear($this);

        //Compara el password ingresado con el de la base de datos
        $resultado = password_verify($password, $this->password);
        //debuguear($resultado);

        if(!$resultado || !$this->confirmado){
            //debuguear('El usuario no esta confirmado');
            self::$alertas['error'][] = 'Password incorrecto o tu cuenta no ha sido confirmada';
        }else{
            //debuguear('El usuario ya esta confirmado');
            return true;
        }
    }
}