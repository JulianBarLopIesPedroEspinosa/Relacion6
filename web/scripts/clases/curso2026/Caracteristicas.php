<?php
class Caracteristicas implements Iterator
{

    private array $_datos = [];
    private array $_nombres = [];
    private int $_posicion = 0;
     


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

/*
    rewind(): crea la lista de nombres en orden inverso, con array_reverse(array_keys($this->_datos)), y pone la posición a 0.
    valid(): comprueba que la posición es menor que el número de nombres de esa lista.
    key(): devuelve el nombre que hay en la lista en la posición actual.
    current(): devuelve $this->_datos[ ese nombre ].
    next(): suma 1 a la posición.
*/

            /* TO REVIW */
    public function rewind(): void{
        $this->_nombres = array_reverse(array_keys($this->_datos));
        $this->_posicion = 0;
    }

    public function valid(): bool {
        return $this->_posicion < count($this->_nombres);
    }

    public function current(): mixed {
        return $this->_datos[$this->_nombres[$this->_posicion]];
    }

    public function key(): mixed {
        return $this->_nombres[$this->_posicion];
    }

    public function next(): void {
        $this->_posicion++;
    }

public function __toString(): string
{
    $cadena = "";
    foreach ($this->_datos as $nombre => $valor) {
        if (is_bool($valor))
            $valor = $valor ? "true" : "false";
        $cadena .= $nombre . ":" . $valor . ";";
    }
    return $cadena;
}


}
