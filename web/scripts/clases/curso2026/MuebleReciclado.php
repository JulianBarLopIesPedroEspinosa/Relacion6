<?php

// Funcion de la libreria de validacion (espacio de nombres VALFILTER) que usa esta clase
use function VALFILTER\validaEntero;

/**
 * Clase MuebleReciclado.
 * Hereda de MuebleBase y añade la propiedad PorcentajeReciclado
 * (entero entre 0 y 100, por defecto 10).
 * Es final: no se pueden crear subclases de ella.
 */
final class MuebleReciclado extends MuebleBase
{
    // Porcentaje de material reciclado del mueble
    private int $_porcentajeReciclado;


    /**
     * Constructor. Recibe los mismos parametros que MuebleBase mas el porcentaje.
     * Si el porcentaje no es valido se asigna el valor por defecto (10).
     * (sin #[Override]: PHP no lo admite en los constructores)
     *
     * @param int $porcentaje porcentaje reciclado (por defecto 10)
     * @throws Exception las mismas que el constructor de MuebleBase
     */
    public function __construct(string $nombre, string $fabricante = "FaMu:", string $pais = "ESPAÑA",
        string $fechaIniVenta = "01/01/2020", string $fechaFinVenta = "31/12/2040", int $anio = 2020,
        float $precio = 30, int $porcentaje = 10)
    {
        // Primero se asignan las propiedades comunes en el constructor del padre
        parent::__construct($nombre, $fabricante, $pais, $fechaIniVenta, $fechaFinVenta, $anio, $precio);

        // Despues la propiedad propia: si falla el set, valor por defecto
        if (!$this->setPorcentajeReciclado($porcentaje)) {
            $this->_porcentajeReciclado = 10;
        }
    }


    public function getPorcentajeReciclado(): int {return $this->_porcentajeReciclado;}

    /**
     * PorcentajeReciclado: entero entre 0 y 100.
     *
     * @param int $percent valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setPorcentajeReciclado(int $percent): bool
    {
        if (!validaEntero($percent, 0, 100, 10)) {
            return false;
        }
        $this->_porcentajeReciclado = $percent;
        return true;
    }


    /**
     * Propiedades que esta clase añade a MuebleBase
     * (deben coincidir con el nombre de sus metodos get/set)
     */
    public function damePropiedadesAdicionales(): array
    {
        return ["PorcentajeReciclado"];
    }

    /**
     * Cadena de MuebleBase mas el porcentaje reciclado
     */
    public function __toString(): string
    {
        return parent::__toString() . ", porcentaje reciclado " . $this->getPorcentajeReciclado() . ".";
    }
}
