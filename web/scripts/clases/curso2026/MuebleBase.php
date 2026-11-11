<?php

use function VALFILTER\validaCadena;
use function VALFILTER\validaEntero;
use function VALFILTER\validaFecha;
use function VALFILTER\validaReal;

require_once $_SERVER['DOCUMENT_ROOT'] . '/web/scripts/libreria/val_filter.php';
abstract class MuebleBase
{
    public const MAXIMO_MUEBLES = 10;

    private static int $_mueblesCreados = 0;
    protected string $_nombre, $_fabricante, $_pais,
        $_fechaIniVenta, $_fechaFinVenta ;
    protected int $_anio;
    protected float $_precio;



/**
 * Constructor de MuebleBase.
 * Asigna las propiedades mediante los métodos set. Si setNombre falla
 * se lanza una excepción; si falla cualquier otro set, se asigna el
 * valor por defecto.
 *
 * @param string $nombre nombre del mueble (obligatorio)
 * @param string $fabricante fabricante (por defecto 'FaMu:')
 * @param string $pais país de fabricación (por defecto 'ESPAÑA')
 * @param string $fechaIniVenta fecha de inicio de venta (por defecto '01/01/2020')
 * @param string $fechaFinVenta fecha de fin de venta (por defecto '31/12/2040')
 * @param int $anio año de inicio de fabricación (por defecto 2020)
 * @param float $precio precio (por defecto 30)
 * @throws Exception si se ha alcanzado el máximo de muebles o el nombre no es válido
 */
public function __construct(
    string $nombre,
    string $fabricante = "FaMu:",
    string $pais = "ESPAÑA",
    string $fechaIniVenta = "01/01/2020",
    string $fechaFinVenta = "31/12/2040",
    int $anio = 2020,
    float $precio = 30
) {
    // Si ya se ha llegado al máximo no se crea el mueble
    if (self::$_mueblesCreados >= self::MAXIMO_MUEBLES) {
        throw new \Exception("Se ha alcanzado el máximo de " . self::MAXIMO_MUEBLES . " muebles");
    }

    // El nombre es obligatorio: si no es válido, error
    if (!$this->setNombre($nombre)) {
        throw new \Exception("El nombre del mueble no es válido");
    }

    // Resto de propiedades: si falla el set se asigna el valor por defecto
    if (!$this->setFabricante($fabricante)) {
        $this->_fabricante = "FaMu:";
    }

    if (!$this->setPais($pais)) {
        $this->_pais = "ESPAÑA";
    }

    // El año va antes que las fechas porque FechaIniVenta depende de él
    if (!$this->setAnio($anio)) {
        $this->_anio = 2020;
    }

    if (!$this->setFechaIniVenta($fechaIniVenta)) {
        $this->_fechaIniVenta = "01/01/" . $this->_anio;
    }

    // FechaFinVenta va después porque depende de FechaIniVenta
    if (!$this->setFechaFinVenta($fechaFinVenta)) {
        $this->_fechaFinVenta = "31/12/2040";
    }

    if (!$this->setPrecio($precio)) {
        $this->_precio = 30;
    }

    // Todo correcto: se cuenta el mueble creado
    self::$_mueblesCreados++;
}

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
        if ($nombre == "") return false;

        //Valido la cadena y si es true la asigno pasandola a mayusculas y quitando espacios
        if (validaCadena($nombre, 40, "")) {

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
        // Quitamos espacios
        $fabricante = trim($fabricante);

        // Si no empieza por FaMu: se lo añadimos
        if (!str_starts_with($fabricante, "FaMu:")) {
            $fabricante = "FaMu:" . $fabricante;
        }

        // Si después de FaMu: no hay nada, no es válido
        if ($fabricante == "FaMu:") return false;

        // Recortamos a 30 caracteres (si es más corto no cambia) y asignamos
        $this->_fabricante = mb_substr($fabricante, 0, 30);
        return true;
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
        if ($pais == "") return false;

        //Valido la cadena y si es true la asigno  
        if (validaCadena($pais, 20, "")) {
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
        //Valido la cadena y si es true la asigno  
        if (validaEntero($anio, 2020, ((int) date('Y')) , 2020)) {
            $this->_anio = $anio;
            return true;
        }

        return false;
    }



    /**
     * Asigna el valor a la propiedad precio si cumple las restricciones
     *
     * @param float $precio valor a asignar
     * @return bool true si se ha asignado, false si no es válido
     */
    public function setPrecio(float $precio): bool
    {


        //Valido la cadena y si es true la asigno  
        if (validaReal($precio, 30, PHP_FLOAT_MAX, 30)) {
            $this->_precio = $precio;
            return true;
        }

        return false;
    }

    /**
     * Asigna el valor a la propiedad Propiedad si cumple las restricciones
     *
     * @param string $fecha valor a asignar
     * @return bool true si se ha asignado, false si no es válido
     */
    public function setFechaIniVenta(string $fecha): bool
    {
        //Creamos una variable que sera el minimo de fecha posible
        $min = "01/01/" . $this->_anio;

        //Quitamos espacios
        $fecha = trim($fecha);

        //validar que no este vacio
        if ($fecha == "") return false;

        //Valido la fecha y si es true la volvemos a verificar que sea 
        if (validaFecha($fecha, $min)) {    

            //Convertimos a DateTime para poder comparar fechas
            $dtFecha  = \DateTime::createFromFormat('!d/m/Y', $fecha);
            $dtMinima = \DateTime::createFromFormat('!d/m/Y', $min);

            // No puede ser anterior a la fecha mínima
            if ($dtFecha >= $dtMinima) {
                $this->_fechaIniVenta = $fecha;
                return true;
            }
        }

        return false;
    }

    /**
     * Asigna el valor a la propiedad Propiedad si cumple las restricciones
     *
     * @param string $fecha valor a asignar
     * @return bool true si se ha asignado, false si no es válido
     */
    public function setFechaFinVenta(string $fecha): bool
    {
        
        //Quitamos espacios
        $fecha = trim($fecha);

        //validar que no este vacio
        if ($fecha == "") return false;

        //Valido la fecha y si es true la volvemos a verificar que sea 
        if (validaFecha($fecha, "31/12/2040")) {    

            //Convertimos a DateTime para poder comparar fechas
            $dtFecha  = \DateTime::createFromFormat('!d/m/Y', $fecha);
            $dtMinima = \DateTime::createFromFormat('!d/m/Y', $this->_fechaIniVenta);

            // No puede ser anterior a la fecha mínima
            if ($dtFecha >= $dtMinima) {
                $this->_fechaFinVenta = $fecha;
                return true;
            }
        }

        return false;
    }

    /*Esta clase no permite la sobrecarga dinámica de propiedades

    public function __set(string $nombre, mixed $valor): void{throw new \Exception("No existe la propiedad " . $nombre);}

    public function __get(string $nombre): mixed{throw new \Exception("No existe la propiedad " . $nombre);}

    public function __isset(string $nombre): bool{return false;}

    public function __unset(string $nombre): void{throw new \Exception("No existe la propiedad " . $nombre);}

*/
    public function __set(string $nombre, mixed $valor): void{throw new \Exception("No existe la propiedad " . $nombre);}

    public function __get(string $nombre): mixed{throw new \Exception("No existe la propiedad " . $nombre);}

    public function __isset(string $nombre): bool{return false;}

    public function __unset(string $nombre): void{throw new \Exception("No existe la propiedad " . $nombre);}



    //Será redefinido en las clases hijas y devolverá
    //un array con el nombre de las propiedades adicionales a la clase base
    abstract public function damePropiedadesAdicionales();

}
