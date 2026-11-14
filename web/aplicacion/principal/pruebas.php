<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
//Controlador

$barra = [
    [
        "TEXTO" => "inicio",
        "ENLACE" => "/aplicacion/principal/index.php",
    ],
    [
        "TEXTO" => "Pruebas de las clases",
    ]
];



//Datos de ejemplo
$muebles = [];

$muebles[1] = new MuebleTradicional("mesa de comedor", "Roble Hermanos", "ESPAÑA",
    "15/03/2021", "31/12/2030", 2021, 450.5, 60, "S10");
$muebles[1]->anadirCaracteristicas("madera", "roble", "extensible", true, "plazas", 8);

$muebles[2] = new MuebleReciclado("silla eco", "FaMu:Verde S.L.", "PORTUGAL",
    "01/06/2022", "31/12/2035", 2022, 75, 80);
$muebles[2]->anadirCaracteristicas("material", "plastico reciclado", "apilable", true);

$muebles[3] = new MuebleTradicional("armario ropero", "Muebles del Sur", "ESPAÑA",
    "10/01/2020", "31/12/2040", 2020, 899.99, 120, "S02");
$muebles[3]->anadirCaracteristicas("puertas", 4, "espejo", true, "color", "nogal");

$muebles[4] = new MuebleReciclado("estanteria palet", "Reutiliza", "FRANCIA",
    "01/02/2023", "31/12/2033", 2023, 120, 100);
$muebles[4]->anadirCaracteristicas("baldas", 5, "tratada", "barniz al agua", "ULTIMA", "si");

$muebles[5] = new MuebleTradicional("cama nido", "Descanso Antequera", "ESPAÑA",
    "01/09/2024", "31/12/2034", 2024, 650, 85.5, "S05");
$muebles[5]->anadirCaracteristicas("medidas", "90x190", "cajones", 2, "somier", true);


//dibuja la plantilla de la vista
inicioCabecera("TIENDA - Pruebas");
cabecera();
finCabecera();
inicioCuerpo("TIENDA DE MUEBLES - Pruebas", $barra);
cuerpo($muebles);  //llamo a la vista
finCuerpo();
// **********************************************************

//vista
function cabecera()
{
?>
    <!-- esto va en el head -->
<?php
}

/**
 * Muestra una linea de resultado escapando el texto
 */
function linea(string $texto): void
{
    echo htmlspecialchars($texto) . "<br>\n";
}

/**
 * Convierte un booleano en texto para mostrarlo
 */
function tf(bool $valor): string
{
    return $valor ? "true" : "false";
}

//vista
function cuerpo(array $muebles)
{
?>
    <main id="main">
        <h2>Pruebas de las clases de la tienda</h2>

        <h3>1. Constante y metodo de clase puedeCrear</h3>
        <div class="resultado">
            <?php
            linea("MuebleBase::MAXIMO_MUEBLES = " . MuebleBase::MAXIMO_MUEBLES);
            $puede = MuebleBase::puedeCrear($quedan);
            linea("Ya hay " . count($muebles) . " muebles creados en datos.php");
            linea("puedeCrear() = " . tf($puede) . ", se pueden crear todavia: $quedan");
            ?>
        </div>

        <h3>2. Constructor con valores por defecto</h3>
        <div class="resultado">
            <?php
            $r = new MuebleReciclado("taburete");
            linea($r);
            $t = new MuebleTradicional("comoda");
            linea($t);
            ?>
        </div>

        <h3>3. Constructor con valores incorrectos (se ponen los de defecto)</h3>
        <div class="resultado">
            <?php
            // pais demasiado largo, anio fuera de rango, fecha inicio anterior al anio,
            // fecha fin anterior a la de inicio, precio menor de 30, porcentaje > 100
            $mal = new MuebleReciclado(
                "banco",
                "Fabricante con un nombre larguisimo que se recorta",
                "PAIS CON UN NOMBRE DEMASIADO LARGO",
                "05/05/2019",
                "01/01/2000",
                2015,
                10,
                150
            );
            linea($mal);
            linea("Fabricante recortado (" . mb_strlen($mal->getFabricante()) . " caracteres): " . $mal->getFabricante());

            // anio correcto pero fecha de inicio anterior: se pone 01/01/anio
            $mal2 = new MuebleTradicional("perchero", "FaMu:Ya lo tiene", "ITALIA", "31/12/2021",
                "31/12/2040", 2022, 40, 500, "");
            linea($mal2);
            ?>
        </div>

        <h3>4. Constructor con nombre incorrecto (lanza excepcion)</h3>
        <div class="resultado">
            <?php
            try {
                $vacio = new MuebleTradicional("   ");
                linea("ERROR: se ha creado");
            } catch (Exception $e) {
                linea("Excepcion: " . $e->getMessage());
            }
            try {
                $largo = new MuebleTradicional(str_repeat("A", 41));
                linea("ERROR: se ha creado");
            } catch (Exception $e) {
                linea("Excepcion: " . $e->getMessage());
            }
            ?>
        </div>

        <h3>5. Metodos set/get</h3>
        <div class="resultado">
            <?php
            linea("setNombre('mesilla') = " . tf($t->setNombre("mesilla")) . " -> " . $t->getNombre());
            linea("setNombre('') = " . tf($t->setNombre("")) . " -> " . $t->getNombre());
            linea("setFabricante('Pino Norte') = " . tf($t->setFabricante("Pino Norte")) . " -> " . $t->getFabricante());
            linea("setFabricante('FaMu:Pino Norte') = " . tf($t->setFabricante("FaMu:Pino Norte")) . " -> " . $t->getFabricante());
            linea("setPais('SUECIA') = " . tf($t->setPais("SUECIA")) . " -> " . $t->getPais());
            linea("setPais(21 caracteres) = " . tf($t->setPais(str_repeat("X", 21))) . " -> " . $t->getPais());
            linea("setAnio(2023) = " . tf($t->setAnio(2023)) . " -> " . $t->getAnio());
            linea("setAnio(2019) = " . tf($t->setAnio(2019)) . " -> " . $t->getAnio());
            linea("setAnio(" . (intval(date("Y")) + 1) . ") = " . tf($t->setAnio(intval(date("Y")) + 1)) . " -> " . $t->getAnio());
            linea("setFechaIniVenta('1/3/2023') = " . tf($t->setFechaIniVenta("1/3/2023")) . " -> " . $t->getFechaIniVenta());
            linea("setFechaIniVenta('31/12/2022') = " . tf($t->setFechaIniVenta("31/12/2022")) . " -> " . $t->getFechaIniVenta());
            linea("setFechaIniVenta('30/02/2024') = " . tf($t->setFechaIniVenta("30/02/2024")) . " -> " . $t->getFechaIniVenta());
            linea("setFechaFinVenta('31/12/2029') = " . tf($t->setFechaFinVenta("31/12/2029")) . " -> " . $t->getFechaFinVenta());
            linea("setFechaFinVenta('01/01/2023') = " . tf($t->setFechaFinVenta("01/01/2023")) . " -> " . $t->getFechaFinVenta());
            linea("setPrecio(99.9) = " . tf($t->setPrecio(99.9)) . " -> " . $t->getPrecio());
            linea("setPrecio(29.99) = " . tf($t->setPrecio(29.99)) . " -> " . $t->getPrecio());
            linea("setPeso(250) = " . tf($t->setPeso(250)) . " -> " . $t->getPeso());
            linea("setPeso(301) = " . tf($t->setPeso(301)) . " -> " . $t->getPeso());
            linea("setSerie('S99') = " . tf($t->setSerie("S99")) . " -> " . $t->getSerie());
            linea("setSerie('') = " . tf($t->setSerie("")) . " -> " . $t->getSerie());
            linea("setPorcentajeReciclado(55) = " . tf($r->setPorcentajeReciclado(55)) . " -> " . $r->getPorcentajeReciclado());
            linea("setPorcentajeReciclado(-1) = " . tf($r->setPorcentajeReciclado(-1)) . " -> " . $r->getPorcentajeReciclado());
            ?>
        </div>

        <h3>6. No se permite la sobrecarga dinamica de propiedades</h3>
        <div class="resultado">
            <?php
            try {
                $t->color = "rojo";
                linea("ERROR: se ha creado la propiedad");
            } catch (Exception $e) {
                linea("Excepcion al escribir: " . $e->getMessage());
            }
            try {
                echo $t->nombre;
                linea("ERROR: se ha leido la propiedad");
            } catch (Exception $e) {
                linea("Excepcion al leer: " . $e->getMessage());
            }
            ?>
        </div>

        <h3>7. damePropiedadesAdicionales</h3>
        <div class="resultado">
            <?php
            linea("MuebleReciclado: " . implode(", ", $r->damePropiedadesAdicionales()));
            linea("MuebleTradicional: " . implode(", ", $t->damePropiedadesAdicionales()));
            // se usan para llamar a los get de forma dinamica
            foreach ($t->damePropiedadesAdicionales() as $prop) {
                $metodo = "get" . $prop;
                linea("  $metodo() = " . $t->$metodo());
            }
            ?>
        </div>

        <h3>8. Caracteristicas</h3>
        <div class="resultado">
            <?php
            // numero impar de argumentos: se desecha el ultimo ("sobra")
            $t->anadirCaracteristicas("saliente", true, "precio", 300, "sobra");
            linea("anadirCaracteristicas('saliente', true, 'precio', 300, 'sobra')");
            linea("getCaracteristicas() (como cadena): " . $t->getCaracteristicas());

            // modificar una existente
            $t->anadirCaracteristicas("precio", 350);
            linea("Modifico precio -> " . $t->getCaracteristicas());

            // foreach en orden inverso
            $t->anadirCaracteristicas("color", "blanco");
            echo "Recorrido con foreach (orden inverso):<br>\n";
            foreach ($t->getCaracteristicas() as $nombre => $valor) {
                if (is_bool($valor)) $valor = $valor ? "true" : "false";
                linea("  - $nombre = " . $valor);
            }

            // ULTIMA
            $t->anadirCaracteristicas("ULTIMA", "fin");
            linea("Anado ULTIMA -> " . $t->getCaracteristicas());
            try {
                $t->anadirCaracteristicas("nueva", 1);
                linea("ERROR: se ha anadido");
            } catch (Exception $e) {
                linea("Excepcion al anadir otra: " . $e->getMessage());
            }
            $t->anadirCaracteristicas("color", "negro");
            linea("Pero si se puede modificar color -> " . $t->getCaracteristicas());

            // uso directo de la clase
            $c = new Caracteristicas();
            $c->alto = 120;
            $c->ancho = 60;
            linea("Caracteristicas directas: " . $c . " (alto = " . $c->alto . ")");
            ?>
        </div>

        <h3>9. Conversion a cadena</h3>
        <div class="resultado">
            <?php
            linea($t);
            linea($r);
            foreach ($muebles as $indice => $m) {
                linea("[$indice] " . $m);
            }
            ?>
        </div>

        <h3>10. Maximo de instancias</h3>
        <div class="resultado">
            <?php
            MuebleBase::puedeCrear($quedan);
            linea("Antes del bucle se pueden crear: $quedan");
            try {
                $i = 1;
                while (true) {
                    new MuebleReciclado("relleno $i");
                    MuebleBase::puedeCrear($quedan);
                    linea("Creado relleno $i, quedan $quedan");
                    $i++;
                }
            } catch (Exception $e) {
                linea("Excepcion: " . $e->getMessage());
            }
            linea("puedeCrear() = " . tf(MuebleBase::puedeCrear($quedan)) . ", quedan $quedan");
            ?>
        </div>
    </main>
<?php
}
