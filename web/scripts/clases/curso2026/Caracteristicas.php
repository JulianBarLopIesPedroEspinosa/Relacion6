<?php

/**
 * Clase Caracteristicas.
 * Guarda otras caracteristicas de un mueble, creadas de forma dinamica
 * (sobrecarga de propiedades con __get/__set).
 *
 * - Se puede recorrer con foreach: devuelve las caracteristicas en orden
 *   inverso a como se incorporaron (implementa Iterator).
 * - Si se indica la caracteristica ULTIMA no se pueden añadir nuevas,
 *   pero si modificar las que ya existen.
 * - Al convertirla en cadena devuelve car1:valor1;car2:valor2;...
 */
class Caracteristicas implements Iterator
{
    // Caracteristicas guardadas: nombre => valor, en orden de incorporacion
    private array $_datos = [];

    // Para el recorrido con foreach: nombres en orden inverso y posicion actual
    private array $_nombres = [];
    private int $_posicion = 0;


    /* ---------- Sobrecarga de propiedades ---------- */

    // Se llama automaticamente al leer una propiedad que no existe o no es visible
    public function __get(string $nombre): mixed
    {
        if (array_key_exists($nombre, $this->_datos))
            return $this->_datos[$nombre];
        else
            // si no existe lanza una excepcion
            throw new Exception('No existe la caracteristica ' . $nombre);
    }

    // Se llama automaticamente al escribir una propiedad que no existe o no es visible
    public function __set(string $nombre, mixed $valor): void
    {
        // Si es nueva y ya se ha indicado ULTIMA, no se permite
        // (si ya existe se deja modificar aunque este ULTIMA)
        if (!array_key_exists($nombre, $this->_datos) && array_key_exists("ULTIMA", $this->_datos))
            throw new Exception('No se pueden añadir caracteristicas despues de ULTIMA.');

        // Creo o actualizo la caracteristica
        $this->_datos[$nombre] = $valor;
    }

    // Se llama automaticamente al ejecutar isset o empty
    public function __isset(string $nombre): bool
    {
        return (isset($this->_datos[$nombre]));
    }

    // Se llama automaticamente al ejecutar unset
    public function __unset(string $nombre): void
    {
        unset($this->_datos[$nombre]);
    }


    /* ---------- Metodos de Iterator (recorrido con foreach en orden inverso) ----------
     * foreach llama primero a rewind() y despues, en cada vuelta:
     * valid() -> current() y key() -> next()
     */

    // Al empezar el foreach: lista de nombres en orden inverso y posicion a 0
    public function rewind(): void
    {
        $this->_nombres = array_reverse(array_keys($this->_datos));
        $this->_posicion = 0;
    }

    // Indica si quedan elementos por recorrer
    public function valid(): bool
    {
        return $this->_posicion < count($this->_nombres);
    }

    // Valor de la caracteristica en la posicion actual
    public function current(): mixed
    {
        return $this->_datos[$this->_nombres[$this->_posicion]];
    }

    // Nombre de la caracteristica en la posicion actual
    public function key(): mixed
    {
        return $this->_nombres[$this->_posicion];
    }

    // Pasa a la siguiente caracteristica
    public function next(): void
    {
        $this->_posicion++;
    }


    /**
     * Devuelve car1:valor1;car2:valor2;... en el orden en que se añadieron.
     * Los booleanos se pasan a "true"/"false" porque al concatenarlos
     * saldrian "1" o "" (cadena vacia).
     */
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
