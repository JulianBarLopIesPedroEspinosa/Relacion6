<?php

// Funcion de la libreria de validacion (espacio de nombres VALFILTER) que usa esta clase
use function VALFILTER\validaReal;

/**
 * Clase MuebleTradicional.
 * Hereda de MuebleBase y añade las propiedades:
 *  - Peso: real entre 15 y 300 (por defecto 15)
 *  - Serie: cadena, no puede estar vacia (por defecto "S01")
 * Es final: no se pueden crear subclases de ella.
 */
final class MuebleTradicional extends MuebleBase
{
    // Peso del mueble
    private float $_peso;

    // Serie a la que pertenece el mueble
    private string $_serie;


    /**
     * Constructor. Recibe los mismos parametros que MuebleBase mas el peso y la serie.
     * Si alguno de los dos no es valido se asigna su valor por defecto.
     * (sin #[Override]: PHP no lo admite en los constructores)
     *
     * @param float $peso peso (por defecto 15)
     * @param string $serie serie (por defecto "S01")
     * @throws Exception las mismas que el constructor de MuebleBase
     */
    public function __construct(
        string $nombre,
        string $fabricante = "FaMu:",
        string $pais = "ESPAÑA",
        string $fechaIniVenta = "01/01/2020",
        string $fechaFinVenta = "31/12/2040",
        int $anio = 2020,
        float $precio = 30,
        int $material = 10,
        float $peso = 15,
        string $serie = "S01"
        

    ) {
        // Primero se asignan las propiedades comunes en el constructor del padre
        parent::__construct($nombre, $fabricante, $pais, $fechaIniVenta, $fechaFinVenta, $anio, $precio, $material);

        // Despues las propiedades propias: si falla el set, valor por defecto
        if (!$this->setPeso($peso)) {
            $this->_peso = 15;
        }
        if (!$this->setSerie($serie)) {
            $this->_serie = "S01";
        }
    }


    public function getPeso(): float {return $this->_peso;}
    public function getSerie(): string {return $this->_serie;}

    /**
     * Peso: real entre 15 y 300.
     *
     * @param float $peso valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setPeso(float $peso): bool
    {
        if (!validaReal($peso, 15, 300, 15)) {
            return false;
        }
        $this->_peso = $peso;
        return true;
    }

    /**
     * Serie: cadena que no puede estar vacia
     * (el enunciado no indica longitud maxima).
     *
     * @param string $serie valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setSerie(string $serie): bool
    {
        // Quitamos espacios
        $serie = trim($serie);

        // No puede estar vacia
        if ($serie == "") return false;

        $this->_serie = $serie;
        return true;
    }


    /**
     * Propiedades que esta clase añade a MuebleBase
     * (deben coincidir con el nombre de sus metodos get/set)
     */
    public function damePropiedadesAdicionales(): array
    {
        return ["Peso", "Serie"];
    }

    /**
     * Cadena de MuebleBase mas el peso y la serie
     */
    public function __toString(): string
    {
        return parent::__toString() . ", pesa " . $this->getPeso() . " y su serie es " . $this->getSerie();
    }
}
