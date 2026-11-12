<?php

use function VALFILTER\validaEntero;

final class MuebleReciclado extends MuebleBase
{

    private int $_porcentajeReciclado;


    #[Override]
    public function __construct(string $nombre, string $fabricante = "FaMu:", string $pais = "ESPAÑA",
        string $fechaIniVenta = "01/01/2020", string $fechaFinVenta = "31/12/2040", int $anio = 2020,
        float $precio = 30, int $porcentaje = 10)
    {
        parent::__construct($nombre, $fabricante, $pais, $fechaIniVenta, $fechaFinVenta, $anio, $precio);
        if (!$this->setPorcentajeReciclado($porcentaje)) {
                $this->_porcentajeReciclado = 10;
            }
        }


    /*public function getPorcentajeReciclado(){return $this->_porcentajeReciclado;}*/
    public function getPorcentajeReciclado():int{return $this->_porcentajeReciclado;}

    public function setPorcentajeReciclado(int $percent):bool
    {
        if (!validaEntero($percent, 0, 100, 10)) {
            return false;
        }
        $this->_porcentajeReciclado = $percent;
        return true;
    }


    public function damePropiedadesAdicionales(): array
    {
        return ["PorcentajeReciclado"];
    }

    #[Override]
    public function __toString(): string
    {
        return parent::__toString() . ", porcentaje reciclado " . $this->getPorcentajeReciclado() . ".";
    }
}
