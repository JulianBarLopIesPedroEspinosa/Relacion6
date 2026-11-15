<?php
/*
 * cabecera.php
 * Se incluye al principio de todas las paginas. Prepara lo comun de la aplicacion:
 * configuracion, autocarga de clases, libreria de validacion, plantilla y datos.
 */

// Zona horaria para las funciones de fecha (date, DateTime...)
date_default_timezone_set('Europe/Madrid');

// Ruta de la carpeta raiz del sitio (web), para hacer los include con rutas absolutas
define("RUTABASE", dirname(__FILE__));
//define("MODO_TRABAJO","produccion"); //en "produccion" o en "desarrollo"
define("MODO_TRABAJO", "desarrollo"); //en "produccion" o en "desarrollo"

// En produccion no se muestran errores; en desarrollo se muestran todos
if (MODO_TRABAJO == "produccion")
    error_reporting(0);
else
    error_reporting(E_ALL);

// Autocarga: cuando se usa una clase que no esta cargada, PHP llama a esta funcion
// con su nombre y se incluye el fichero <Clase>.php de la carpeta de clases
spl_autoload_register(function ($clase) {
    $ruta = RUTABASE . "/scripts/clases/curso2026/";   // con / al final
    $fichero = $ruta . $clase . ".php";

    if (file_exists($fichero)) {
        require_once($fichero);
    } else {
        throw new Exception("La clase $clase no se ha encontrado en $fichero");
    }
});

// Libreria de validacion (espacio de nombres VALFILTER).
// Se carga aqui una sola vez para que la usen las clases y las paginas
include_once(RUTABASE . "/scripts/libreria/val_filter.php");

// Plantilla de la vista (inicioCabecera, inicioCuerpo, finCuerpo...)
include_once(RUTABASE . "/aplicacion/plantilla/plantilla.php");

// Creo todos los objetos que necesita mi aplicacion: el array de muebles.
// Se define en un unico punto (datos.php) y asi esta disponible en todas las paginas
include_once(RUTABASE . "/aplicacion/principal/datos.php");
