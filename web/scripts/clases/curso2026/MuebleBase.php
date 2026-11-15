<?php

/*
 * Funciones de la libreria de validacion (val_filter.php) que usa esta clase.
 * La libreria se carga en cabecera.php; como tiene el espacio de nombres VALFILTER,
 * se importan aqui con "use function" para llamarlas sin el prefijo.
 */
use function VALFILTER\validaCadena;
use function VALFILTER\validaEntero;
use function VALFILTER\validaFecha;
use function VALFILTER\validaReal;

/**
 * Clase abstracta MuebleBase.
 * Base de todos los muebles de la tienda (MuebleReciclado y MuebleTradicional).
 *
 * - Las propiedades no son accesibles directamente: se usan los metodos set/get.
 * - Los set validan el valor: si es correcto lo asignan y devuelven true;
 *   si no, no asignan nada y devuelven false.
 * - No permite la sobrecarga dinamica de propiedades.
 * - Controla cuantos muebles se han creado (maximo MAXIMO_MUEBLES).
 * - Cada mueble tiene un objeto Caracteristicas con caracteristicas extra.
 */
abstract class MuebleBase
{
    // Numero maximo de muebles (instancias) que se pueden crear
    public const int MAXIMO_MUEBLES = 10;

    // Variable de clase: muebles creados hasta el momento (comun a todas las instancias)
    private static int $_mueblesCreados = 0;

    // Propiedades del mueble (solo accesibles con los metodos set/get)
    protected string $_nombre, $_fabricante, $_pais,
        $_fechaIniVenta, $_fechaFinVenta;
    protected int $_anio;
    protected float $_precio;

    // Caracteristicas adicionales del mueble (se crean de forma dinamica)
    private Caracteristicas $_caracteristicas;


    /**
     * Constructor de MuebleBase.
     * Asigna las propiedades mediante los metodos set. Si setNombre falla
     * se lanza una excepcion; si falla cualquier otro set, se asigna el
     * valor por defecto.
     *
     * @param string $nombre nombre del mueble (obligatorio)
     * @param string $fabricante fabricante (por defecto 'FaMu:')
     * @param string $pais pais de fabricacion (por defecto 'ESPAÑA')
     * @param string $fechaIniVenta fecha de inicio de venta (por defecto '01/01/2020')
     * @param string $fechaFinVenta fecha de fin de venta (por defecto '31/12/2040')
     * @param int $anio año de inicio de fabricacion (por defecto 2020)
     * @param float $precio precio (por defecto 30)
     * @throws Exception si se ha alcanzado el maximo de muebles o el nombre no es valido
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
        // Si ya se ha llegado al maximo no se crea el mueble
        if (self::$_mueblesCreados >= self::MAXIMO_MUEBLES) {
            throw new Exception("Se ha alcanzado el maximo de " . self::MAXIMO_MUEBLES . " muebles");
        }

        // El nombre es obligatorio: si no es valido, error
        if (!$this->setNombre($nombre)) {
            throw new Exception("El nombre del mueble no es valido");
        }

        // Resto de propiedades: si falla el set se asigna el valor por defecto
        if (!$this->setFabricante($fabricante)) {
            $this->_fabricante = "FaMu:";
        }

        if (!$this->setPais($pais)) {
            $this->_pais = "ESPAÑA";
        }

        // El año va antes que las fechas porque FechaIniVenta depende de el
        if (!$this->setAnio($anio)) {
            $this->_anio = 2020;
        }

        // Si la fecha de inicio no es valida se pone el 1 de enero del año de fabricacion
        if (!$this->setFechaIniVenta($fechaIniVenta)) {
            $this->_fechaIniVenta = "01/01/" . $this->_anio;
        }

        // FechaFinVenta va despues porque depende de FechaIniVenta
        if (!$this->setFechaFinVenta($fechaFinVenta)) {
            $this->_fechaFinVenta = "31/12/2040";
        }

        if (!$this->setPrecio($precio)) {
            $this->_precio = 30;
        }

        // Objeto vacio donde se iran guardando las caracteristicas
        $this->_caracteristicas = new Caracteristicas();

        // Todo correcto: se cuenta el mueble creado
        self::$_mueblesCreados++;
    }


    /* ---------- Metodos get: devuelven el valor de cada propiedad ---------- */

    public function getNombre(): string {return $this->_nombre;}
    public function getFabricante(): string {return $this->_fabricante;}
    public function getPais(): string {return $this->_pais;}
    public function getFechaIniVenta(): string {return $this->_fechaIniVenta;}
    public function getFechaFinVenta(): string {return $this->_fechaFinVenta;}
    public function getAnio(): int {return $this->_anio;}
    public function getPrecio(): float {return $this->_precio;}

    /**
     * Devuelve las caracteristicas del mueble como objeto
     * (se puede recorrer con foreach o mostrar con echo)
     */
    public function getCaracteristicas(): Caracteristicas {return $this->_caracteristicas;}


    /* ---------- Metodos set: validan y asignan cada propiedad ---------- */

    /**
     * Nombre: obligatorio, no puede estar vacio, maximo 40 caracteres.
     * Se guarda en mayusculas.
     *
     * @param string $nombre valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setNombre(string $nombre): bool
    {
        // Quitamos los espacios del principio y del final
        $nombre = trim($nombre);

        // No puede estar vacio
        if ($nombre == "") return false;

        // Maximo 40 caracteres: si es correcto se asigna en mayusculas
        if (validaCadena($nombre, 40, "")) {
            $this->_nombre = mb_strtoupper($nombre);
            return true;
        }

        return false;
    }

    /**
     * Fabricante: siempre empieza por "FaMu:" (se añade si no lo tiene)
     * y se recorta a 30 caracteres.
     *
     * @param string $fabricante valor a asignar
     * @return bool true si se ha asignado, false si despues de "FaMu:" no hay nada
     */
    public function setFabricante(string $fabricante): bool
    {
        // Quitamos espacios
        $fabricante = trim($fabricante);

        // Si no empieza por FaMu: se lo añadimos
        if (!str_starts_with($fabricante, "FaMu:")) {
            $fabricante = "FaMu:" . $fabricante;
        }

        // Si despues de FaMu: no hay nada, no es valido
        // (el constructor asigna entonces el valor por defecto "FaMu:")
        if ($fabricante == "FaMu:") return false;

        // Recortamos a 30 caracteres (si es mas corto no cambia) y asignamos.
        // mb_substr para que la ñ y las tildes cuenten como un caracter
        $this->_fabricante = mb_substr($fabricante, 0, 30);
        return true;
    }

    /**
     * Pais: no puede estar vacio, maximo 20 caracteres.
     *
     * @param string $pais valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setPais(string $pais): bool
    {
        // Quitamos espacios
        $pais = trim($pais);

        // No puede estar vacio
        if ($pais == "") return false;

        // Maximo 20 caracteres
        if (validaCadena($pais, 20, "")) {
            $this->_pais = $pais;
            return true;
        }

        return false;
    }

    /**
     * Año de inicio de fabricacion: entre 2020 y el año actual.
     *
     * @param int $anio valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setAnio(int $anio): bool
    {
        // El maximo es el año actual: date('Y') lo devuelve como cadena, por eso el (int)
        if (validaEntero($anio, 2020, ((int) date('Y')), 2020)) {
            $this->_anio = $anio;
            return true;
        }

        return false;
    }

    /**
     * Precio: real, no puede ser menor de 30.
     *
     * @param float $precio valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setPrecio(float $precio): bool
    {
        // No hay maximo: se usa el mayor real posible (PHP_FLOAT_MAX)
        if (validaReal($precio, 30, PHP_FLOAT_MAX, 30)) {
            $this->_precio = $precio;
            return true;
        }

        return false;
    }

    /**
     * Fecha de inicio de venta (dd/mm/aaaa): no puede ser anterior
     * al 1 de enero del año de fabricacion.
     *
     * @param string $fecha valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setFechaIniVenta(string $fecha): bool
    {
        // Fecha minima posible: 1 de enero del año de fabricacion
        $min = "01/01/" . $this->_anio;

        // Quitamos espacios
        $fecha = trim($fecha);

        // No puede estar vacia
        if ($fecha == "") return false;

        // Comprueba que es una fecha valida y la sanea (1/2/2023 -> 01/02/2023)
        if (validaFecha($fecha, $min)) {

            // Convertimos a DateTime para poder comparar fechas.
            // El ! pone la hora a 00:00:00 para que solo se compare el dia
            $dtFecha  = \DateTime::createFromFormat('!d/m/Y', $fecha);
            $dtMinima = \DateTime::createFromFormat('!d/m/Y', $min);

            // No puede ser anterior a la fecha minima
            if ($dtFecha >= $dtMinima) {
                $this->_fechaIniVenta = $fecha;
                return true;
            }
        }

        return false;
    }

    /**
     * Fecha de fin de venta (dd/mm/aaaa): no puede ser anterior
     * a la fecha de inicio de venta.
     *
     * @param string $fecha valor a asignar
     * @return bool true si se ha asignado, false si no es valido
     */
    public function setFechaFinVenta(string $fecha): bool
    {
        // Quitamos espacios
        $fecha = trim($fecha);

        // No puede estar vacia
        if ($fecha == "") return false;

        // Comprueba que es una fecha valida y la sanea
        if (validaFecha($fecha, "31/12/2040")) {

            // Convertimos a DateTime para poder comparar fechas
            $dtFecha  = \DateTime::createFromFormat('!d/m/Y', $fecha);
            $dtMinima = \DateTime::createFromFormat('!d/m/Y', $this->_fechaIniVenta);

            // No puede ser anterior a la fecha de inicio de venta
            if ($dtFecha >= $dtMinima) {
                $this->_fechaFinVenta = $fecha;
                return true;
            }
        }

        return false;
    }


    /* ---------- Esta clase no permite la sobrecarga dinamica de propiedades ----------
     * Los metodos magicos se llaman cuando se usa una propiedad que no existe o no es
     * visible desde fuera. Lanzan una excepcion para que no se puedan crear, leer
     * ni borrar propiedades nuevas (ej: $mueble->color = "rojo").
     */

    public function __set(string $nombre, mixed $valor): void {throw new \Exception("No existe la propiedad " . $nombre);}

    public function __get(string $nombre): mixed {throw new \Exception("No existe la propiedad " . $nombre);}

    // isset() sobre una propiedad que no existe siempre devuelve false
    public function __isset(string $nombre): bool {return false;}

    public function __unset(string $nombre): void {throw new \Exception("No existe la propiedad " . $nombre);}


    /**
     * Devuelve un array con el nombre de las propiedades que añade cada clase hija.
     * Es abstracto: cada hija esta obligada a definirlo.
     * Con esos nombres se puede llamar a los get/set de forma dinamica ("get" . $prop).
     */
    abstract public function damePropiedadesAdicionales(): array;


    /**
     * Metodo de clase: indica si se pueden crear mas muebles.
     * En $num devuelve (por referencia) cuantos muebles se pueden crear todavia.
     * El parametro es ?int y con valor null por defecto para poder llamarlo con
     * una variable que aun no existe: MuebleBase::puedeCrear($quedan)
     *
     * @param int|null $num numero de muebles que aun se pueden crear
     * @return bool true si se pueden crear mas, false si no
     */
    public static function puedeCrear(?int &$num = null): bool
    {
        $num = self::MAXIMO_MUEBLES - self::$_mueblesCreados;
        return $num > 0;
    }


    /**
     * Añade caracteristicas al mueble. Recibe pares (nombre, valor).
     * Si el numero de argumentos es impar se desecha el ultimo.
     * Ej: anadirCaracteristicas('saliente', true, 'precio', 300)
     *
     * @param mixed ...$argumentos pares nombre, valor
     * @throws Exception si se intenta añadir una nueva despues de ULTIMA
     */
    public function anadirCaracteristicas(mixed ...$argumentos): void
    {
        $total = count($argumentos);

        // Se avanza de 2 en 2; $i + 1 < $total hace que se ignore el ultimo si es impar
        for ($i = 0; $i + 1 < $total; $i += 2) {
            $nombre = strval($argumentos[$i]);

            // Al asignar una propiedad que no existe se llama al __set de Caracteristicas
            $this->_caracteristicas->$nombre = $argumentos[$i + 1];
        }
    }


    /**
     * Al convertir el mueble en cadena devuelve:
     * 'MUEBLE de clase XXX con nombre XXX, fabricante XXX, fabricado en XXX a partir
     * del año 999, vendido desde 99/99/9999 hasta 99/99/9999, precio 9999'
     * seguido de sus caracteristicas.
     * static::class devuelve la clase real del objeto (MuebleReciclado o MuebleTradicional).
     */
    public function __toString(): string
    {
        return "MUEBLE de clase " . static::class . " con nombre " . $this->getNombre()
            . ", fabricante " . $this->getFabricante() . ", fabricado en " . $this->getPais()
            . " a partir del año " . $this->getAnio() . ", vendido desde " . $this->getFechaIniVenta()
            . " hasta " . $this->getFechaFinVenta() . ", precio " . $this->getPrecio()
            . ". Caracteristicas: " . $this->_caracteristicas;
    }
}
