<?php

use function VALFILTER\validaReal;

final class MuebleTradicional extends MuebleBase
{

    private float $_peso;
    private string $_serie;


    #[Override]
    public function __construct(
        string $nombre,
        string $fabricante = "FaMu:",
        string $pais = "ESPAÑA",
        string $fechaIniVenta = "01/01/2020",
        string $fechaFinVenta = "31/12/2040",
        int $anio = 2020,
        float $precio = 30,
        float $peso = 15,
        string $serie = "S01"
    ) {
        parent::__construct($nombre, $fabricante, $pais, $fechaIniVenta, $fechaFinVenta, $anio, $precio);
        if (!$this->setPeso($peso)) {
            $this->_peso = 15;
        }
        if (!$this->setSerie($serie)) {
            $this->_serie = "S01";
        }
    }


    /*
    public function getPeso():float{return $this->_peso;}
    public function getSerie():string{return $this->_serie;}
    */
    public function getPeso():float{return $this->_peso;}
    public function getSerie():string{return $this->_serie;}


    public function setPeso(float $peso): bool
    {
        if (!validaReal($peso, 15, 300, 15)) {
            return false;
        }
        $this->_peso = $peso;
        return true;
    }

    public function setSerie(string $serie): bool
    {
        // Quitamos espacios
        $serie = trim($serie);

        // No puede estar vacía
        if ($serie == "") return false;

        $this->_serie = $serie;
        return true;
    }

    public function damePropiedadesAdicionales(): array
    {
        return ["Peso", "Serie"];
    }

    #[Override]
    public function __toString(): string
    {
        return parent::__toString() . ", pesa " . $this->getPeso() . " y su serie es " . $this->getSerie();
    }
}
