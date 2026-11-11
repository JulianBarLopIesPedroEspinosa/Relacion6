<?php

use function VALFILTER\validaCadena;
use function VALFILTER\validaEntero;

require_once $_SERVER['DOCUMENT_ROOT'] . '/web/scripts/libreria/val_filter.php';
abstract class MuebleBase
{
    public const MAXIMO_MUEBLES = 10;

    private static int $_mueblesCreados = 0;
    protected string $_nombre, $_fabricante = "FaMu:", $_pais = "ESPAÑA",
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
     * @param string $nombre valor a asignar
     * @return bool true si se ha asignado, false si no es válido
     */
    public function setNombre(string $nombre): bool
    {
        //Quitamos espacios

        $nombre = trim($nombre);

        //validar que no este vacio
        if($nombre == "")return false;

       //Valido la cadena y si es true la asigno pasandola a mayusculas y quitando espacios
        if (validaCadena($nombre,40,"")) {
            
            $this->_nombre = mb_strtoupper($nombre);
            return true;
        }

        return false;
    }

    /**
     * Asigna el valor a la propiedad Propiedad si cumple las restricciones
     *
     * @param string $fabricante valor a asignar
     * @return bool true si se ha asignado, false si no es válido
     */
    public function setFabricante(string $fabricante): bool
    {
        //Quitamos espacios
        $fabricante = trim($fabricante);

        //Si esta vacio o despues de FaMu no tiene nada devolvemos false
        if($fabricante == "" || mb_strlen($fabricante)<=5) return false;

        //Si no tiene FaMu: delante se lo añadimos  y verificamos si añadiendolo nos pasamos de los 30 caracteres si es asi la recortamos
        if(!str_starts_with($fabricante,"FaMu:")){
            $fabricante = "FaMu:". $fabricante;
            if(!validaCadena($fabricante,30,"")){
                $this->_fabricante = mb_substr($fabricante,0,30);
                return true;
            }
        }

       //Valido la cadena y si es true la asigno
        if (validaCadena($fabricante,30,"")) {
            
            $this->_fabricante = $fabricante;
            return true;
        }

        return false;
    }
    

    /**
     * Asigna el valor a la propiedad Propiedad si cumple las restricciones
     *
     * @param string $pais valor a asignar
     * @return bool true si se ha asignado, false si no es válido
     */
    public function setPais(string $pais): bool
    {
        //Quitamos espacios
        $pais = trim($pais);

        //validar que no este vacio
        if($pais == "")return false;

       //Valido la cadena y si es true la asigno  
        if (validaCadena($pais,20,"")) {
            $this->_pais = $pais;
            return true;
        }

        return false;
        }


        

        /**
     * Asigna el valor a la propiedad Propiedad si cumple las restricciones
     *
     * @param int $anio valor a asignar
     * @return bool true si se ha asignado, false si no es válido
     */
    public function setAnio(int $anio): bool
    {
        
        //validar que no este vacio
        if($anio == "")return false;

       //Valido la cadena y si es true la asigno  
        if (validaEntero($anio,2020,2026,2020)) {
            $this->_anio = $anio;
            return true;
        }

        return false;
    }


}
