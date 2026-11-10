<?php

class MuebleBase
{
    public const MAXIMO_MUEBLES = 10;

    private static int $_mueblesCreados;
    protected string $_nombre, $_fabricante = "FaMu", $_pais = "ESPAÑA",
        $_fechaIniVenta = "01/01/2020", $_fechaFinVenta = "31/12/2040";
    protected int $_anio = 2020;
    protected float $_precio = 30;

    /*
    public function getNombre(): string {return $this->_nombre;}
    public function getFabricante(): string {return $this->_fabricante;}
    public function getPais(): string {return $this->_pais;}
    public function getFechaIniVenta(): string {return $this->_fechaIniVenta;}   
    public function getFechaFinVenta(): string {return $this->_fechaFinVenta;}
    public function getAnio(): int {return $this->_anio;}
    public function getPrecio(): float {return $this->_precio;}
    */
   
    public function getNombre(): string {return $this->_nombre;}
    public function getFabricante(): string {return $this->_fabricante;}
    public function getPais(): string {return $this->_pais;}
    public function getFechaIniVenta(): string {return $this->_fechaIniVenta;}   
    public function getFechaFinVenta(): string {return $this->_fechaFinVenta;}
    public function getAnio(): int {return $this->_anio;}
    public function getPrecio(): float {return $this->_precio;}

    /**
     * Asigna el valor a la propiedad Propiedad si cumple las restricciones
     *
     * @param string $propiedad valor a asignar
     * @return string true si se ha asignado, false si no es válido
     */
    public function setPropiedad(string $propiedad): string
    {
        // 1. Preparar el valor si hace falta (trim, mayúsculas, añadir prefijo...)

        // 2. Comprobaciones: si algo falla, salir sin asignar
        if (true) {
            return false;
        }

        if (true) {
            return false;
        }

        // 3. Todo correcto: asignar y devolver true
        $this->_propiedad = $propiedad;
        return true;
    }
}
