<?php

class MuebleBase
{
    public const MAXIMO_MUEBLES = 10;

    private static int $_mueblesCreados;
    protected string $_nombre, $_fabricante="FaMu", $_pais="ESPAÑA", 
                        $_fechaIniVenta="01/01/2020", $_fechaFinVenta="31/12/2040";
    protected int $_anio=2020;
    protected float $_precio=30;

   /**
 * Devuelve el valor de la propiedad Propiedad
 *
 * @return bool valor de Propiedad
 */
public function getPropiedad(): bool
{
    return $this->_nombre;
}
    
/**
 * Asigna el valor a la propiedad Propiedad si cumple las restricciones
 *
 * @param bool $propiedad valor a asignar
 * @return bool true si se ha asignado, false si no es válido
 */
public function setPropiedad(bool $propiedad): bool
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
