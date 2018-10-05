<?php

class Conexion //CLASE DE CONECCION
{
    //SE DEFINEN LAS VARIABLES DE CONFIGURACION DE CONEXION
    private $user;
    private $password;
    private $host;
    private $conexion;
    private $DB;

    public function getConexion()
    {
        //PARA ESTA PRACTICA SE UTILIZA EL GESTOR DE BASE DE DATOS POSTGRESQL 

        //PARA CONFIGURAR LA CONEXION SE HACE USO DE LA CLASE PDO DEFINIDA EN PHP 
        //POR MEDIO DE LA CUAL LA CONFIGURACIN DE CONEXION SE REALIZA DE MANERA FACIL 
        //Y SEGURA ADEMAS ESTA CLASE NOS PERMITE REALIZAR CONFIGURACIONES CON CUALQUIER 
        //GESTOR DE BASE DE DATOS 
        // A CONTINUACION SE HACE LA CONFIGURACION CON POSTGRESQL
        $this->user = 'postgres';
        $this->password = 's';
        $this->host = 'localhost';
        $this->DB = 'UserSantiago';

        try
        {
            $this->conexion = new PDO("pgsql:host=$this->host;port=5432;dbname=$this->DB",$this->user,$this->password);
            $this->conexion->exec("SET CHARACTER SET UTF8");
            return $this->conexion;
        }catch (Exception $exc)
        {
            echo $exc->getTraceAsString();
        }

    }
    
}
