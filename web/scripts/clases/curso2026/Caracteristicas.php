<?php
class Caracteristicas
{

    private array $_datos = [];


    //se llamará de forma automática cuando se lea una propiedad y no sea visible.
    public function __get(string $nombre):mixed
    {
        if (array_key_exists($nombre, $this->_datos))
            return $this->_datos[$nombre];
        else
            //si no existe lanza una excepción
            throw new Exception('No existe la característica ' . $nombre);
    }

    //se llamará de forma automática cuando se escriba una propiedad y no sea visible
    public function __set(string $nombre, mixed $valor):void
    {
        //puede filtrar que propiedades están o no disponibles
        if (!array_key_exists($nombre, $this->_datos) && array_key_exists("ULTIMA", $this->_datos))
            throw new Exception('No se pueden añadir características después de ULTIMA.');
        //creo/actualizo la variable dinámica.
        $this->_datos[$nombre] = $valor;
    }

    //se llamará automáticamente al ejecutar isset o empty
    public function __isset(string $nombre): bool
    {
        return (isset($this->_datos[$nombre]));
    }
    //se llamará automáticamente al ejecutar
    //unset
    public function __unset(string $nombre):void
    {
        unset($this->_datos[$nombre]);
    }
}
