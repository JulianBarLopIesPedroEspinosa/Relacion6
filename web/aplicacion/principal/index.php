<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Funciones de la libreria de validacion que usa esta pagina (espacio de nombres VALFILTER)
use function VALFILTER\validaCadena;
use function VALFILTER\validaRango;

//Controlador

$barra = [
    [
        "TEXTO" => "inicio",
        "ENLACE" => "/aplicacion/principal/index.php",
    ]
];

// El array $muebles se define en datos.php (incluido desde cabecera.php)

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
