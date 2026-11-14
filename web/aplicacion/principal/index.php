<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

use function VALFILTER\validaCadena;
use function VALFILTER\validaRango;

//Controlador

$barra = [
    [
        "TEXTO" => "inicio",
        "ENLACE" => "/aplicacion/principal/index.php",
    ]
];

//Datos de ejemplo
$muebles = [];

$muebles[1] = new MuebleTradicional(
    "mesa de comedor",
    "Roble Hermanos",
    "ESPAÑA",
    "15/03/2021",
    "31/12/2030",
    2021,
    450.5,
    60,
    "S10"
);
$muebles[1]->anadirCaracteristicas("madera", "roble", "extensible", true, "plazas", 8);

$muebles[2] = new MuebleReciclado(
    "silla eco",
    "FaMu:Verde S.L.",
    "PORTUGAL",
    "01/06/2022",
    "31/12/2035",
    2022,
    75,
    80
);
$muebles[2]->anadirCaracteristicas("material", "plastico reciclado", "apilable", true);

$muebles[3] = new MuebleTradicional(
    "armario ropero",
    "Muebles del Sur",
    "ESPAÑA",
    "10/01/2020",
    "31/12/2040",
    2020,
    899.99,
    120,
    "S02"
);
$muebles[3]->anadirCaracteristicas("puertas", 4, "espejo", true, "color", "nogal");

$muebles[4] = new MuebleReciclado(
    "estanteria palet",
    "Reutiliza",
    "FRANCIA",
    "01/02/2023",
    "31/12/2033",
    2023,
    120,
    100
);
$muebles[4]->anadirCaracteristicas("baldas", 5, "tratada", "barniz al agua", "ULTIMA", "si");

$muebles[5] = new MuebleTradicional(
    "cama nido",
    "Descanso Antequera",
    "ESPAÑA",
    "01/09/2024",
    "31/12/2034",
    2024,
    650,
    85.5,
    "S05"
);
$muebles[5]->anadirCaracteristicas("medidas", "90x190", "cajones", 2, "somier", true);



// propiedades comunes a todos los muebles (las de MuebleBase)
$propiedadesBase = ["Nombre", "Fabricante", "Pais", "Anio", "FechaIniVenta", "FechaFinVenta", "Precio"];

$seleccionado = 0;   // indice del mueble elegido en el select
$mostrar = [];       // propiedades del mueble a mostrar (nombre => valor)
$errores = [];

// boton Mostrar Mueble
if (isset($_POST["mostrar"])) {
    $indice = intval($_POST["mueble"] ?? 0);

    if (!validaRango($indice, $muebles, 2)) {
        $errores[] = "El mueble seleccionado no existe";
    } else {
        $seleccionado = $indice;
        $mueble = $muebles[$indice];

        $mostrar["Indice"] = $indice;
        $mostrar["Tipo"] = get_class($mueble);

        // propiedades base + adicionales (sabidas con damePropiedadesAdicionales)
        $propiedades = array_merge($propiedadesBase, $mueble->damePropiedadesAdicionales());
        foreach ($propiedades as $prop) {
            $metodo = "get" . $prop;
            $mostrar[$prop] = $mueble->$metodo();
        }
    }
}

//dibuja la plantilla de la vista
inicioCabecera("TIENDA");
cabecera();
finCabecera();
inicioCuerpo("TIENDA DE MUEBLES", $barra);
cuerpo($muebles, $seleccionado, $mostrar, $errores);  //llamo a la vista
finCuerpo();
// **********************************************************

//vista
function cabecera()
{
?>
    <!-- esto va en el head -->
<?php
}

//vista
function cuerpo(array $muebles, int $seleccionado, array $mostrar, array $errores)
{
?>
    <main id="main">
        <h2>Muebles de la tienda</h2>

        <table class="tabla">
            <tr>
                <th>Indice</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Fabricante</th>
                <th>Pais</th>
                <th>Año</th>
                <th>Precio</th>
            </tr>
            <?php foreach ($muebles as $indice => $mueble) { ?>
                <tr>
                    <td><?= $indice ?></td>
                    <td><?= get_class($mueble) ?></td>
                    <td><?= htmlspecialchars($mueble->getNombre()) ?></td>
                    <td><?= htmlspecialchars($mueble->getFabricante()) ?></td>
                    <td><?= htmlspecialchars($mueble->getPais()) ?></td>
                    <td><?= $mueble->getAnio() ?></td>
                    <td><?= number_format($mueble->getPrecio(), 2, ",", ".") ?> €</td>
                </tr>
            <?php } ?>
        </table>

        <h3>Elegir mueble</h3>
        <!-- un solo formulario: Mostrar envia a esta pagina y Modificar abre modificar.php en otra ventana -->
        <form action="index.php" method="post">
            <label for="mueble">Mueble:</label>
            <select name="mueble" id="mueble">
                <?php foreach ($muebles as $indice => $mueble) { ?>
                    <option value="<?= $indice ?>" <?= $indice == $seleccionado ? "selected" : "" ?>>
                        <?= $indice ?> - <?= htmlspecialchars($mueble->getNombre()) ?> (<?= get_class($mueble) ?>)
                    </option>
                <?php } ?>
            </select>
            <br><br>
            <input type="submit" name="mostrar" value="Mostrar Mueble">
            <input type="submit" name="modificar" value="Modificar Mueble"
                formaction="modificar.php" formtarget="_blank">
        </form>

        <?php foreach ($errores as $error) { ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php } ?>

        <?php if ($mostrar) { ?>
            <h3>Datos del mueble</h3>
            <div class="resultado">
                <?php foreach ($mostrar as $prop => $valor) { ?>
                    <strong><?= $prop ?>:</strong> <?= htmlspecialchars(strval($valor)) ?><br>
                <?php } ?>

                <br><strong>Caracteristicas:</strong><br>
                <?php foreach ($muebles[$mostrar["Indice"]]->getCaracteristicas() as $nombre => $valor) {
                    if (is_bool($valor)) $valor = $valor ? "true" : "false"; ?>
                    &nbsp;&nbsp;- <?= htmlspecialchars($nombre) ?>: <?= htmlspecialchars($valor) ?><br>
                <?php } ?>
            </div>
        <?php } ?>
    </main>
<?php
}
