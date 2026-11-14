<?php

use function VALFILTER\validaRango;

include_once(dirname(__FILE__) . "/../../cabecera.php");
//Controlador

$barra = [
    [
        "TEXTO" => "inicio",
        "ENLACE" => "/aplicacion/principal/index.php",
    ],
    [
        "TEXTO" => "Modificar mueble",
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


// textos de las etiquetas del formulario
$etiquetas = [
    "Nombre" => "Nombre (max. 40)",
    "Fabricante" => "Fabricante (max. 30, se antepone FaMu:)",
    "Pais" => "Pais (max. 20)",
    "Anio" => "Año de fabricacion (2020-" . date("Y") . ")",
    "FechaIniVenta" => "Fecha inicio venta (dd/mm/aaaa)",
    "FechaFinVenta" => "Fecha fin venta (dd/mm/aaaa)",
    "Precio" => "Precio (minimo 30)",
    "PorcentajeReciclado" => "Porcentaje reciclado (0-100)",
    "Peso" => "Peso (15-300 kg)",
    "Serie" => "Serie",
];

// tipo de cada propiedad para llamar a los set con el tipo correcto
$tipos = [
    "Anio" => "int",
    "PorcentajeReciclado" => "int",
    "Precio" => "float",
    "Peso" => "float",
];

// ---------- mueble a modificar ----------
$id = intval($_POST["mueble"] ?? 0);
if (!validaRango($id, $muebles, 2)) {
    paginaError("El mueble indicado no existe");
    exit;
}

$mueble = $muebles[$id];
$clase = get_class($mueble);
$propiedades = array_merge(
    ["Nombre", "Fabricante", "Pais", "Anio", "FechaIniVenta", "FechaFinVenta", "Precio"],
    $mueble->damePropiedadesAdicionales()
);

// valores iniciales: los que tiene el mueble
$datos = [];
foreach ($propiedades as $prop) {
    $metodo = "get" . $prop;
    $datos[$prop] = strval($mueble->$metodo());
}

$errores = [];
$correcto = false;

// ---------- se ha enviado el formulario ----------
if (isset($_POST["guardar"])) {

    // recojo los datos enviados
    foreach ($propiedades as $prop) {
        $datos[$prop] = trim($_POST[$prop] ?? "");
    }

    /*
     * PRIMERA MITAD: Nombre, Fabricante, Pais y Anio
     * se validan con una instancia auxiliar del mismo tipo llamando a los set
     */
    try {
        $aux = new $clase($mueble->getNombre());

        if ($aux->setNombre($datos["Nombre"])) {
            $datos["Nombre"] = $aux->getNombre();          // queda en mayusculas
        } else {
            $errores["Nombre"] = "El nombre es obligatorio y tiene como maximo 40 caracteres";
        }

        $aux->setFabricante($datos["Fabricante"]);         // nunca falla, se corrige
        $datos["Fabricante"] = $aux->getFabricante();

        if (!$aux->setPais($datos["Pais"])) {
            $errores["Pais"] = "El pais no puede estar vacio y tiene como maximo 20 caracteres";
        }

        if (!preg_match("/^\d{4}$/", $datos["Anio"]) || !$aux->setAnio(intval($datos["Anio"]))) {
            $errores["Anio"] = "El año debe estar entre 2020 y " . date("Y");
        }
    } catch (Exception $e) {
        $errores["general"] = "No se ha podido crear la instancia auxiliar: " . $e->getMessage();
    }

    /*
     * SEGUNDA MITAD: fechas, precio y propiedades adicionales
     * se validan directamente con condiciones
     */
    $anio = isset($errores["Anio"]) ? $mueble->getAnio() : intval($datos["Anio"]);

    // fecha de inicio de venta
    $fechaIni = null;
    $partes = [];
    if (preg_match("/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/", $datos["FechaIniVenta"], $partes)
        && checkdate(intval($partes[2]), intval($partes[1]), intval($partes[3]))) {
        $datos["FechaIniVenta"] = sprintf("%02d/%02d/%04d", $partes[1], $partes[2], $partes[3]);
        $fechaIni = DateTime::createFromFormat("d/m/Y H:i:s", $datos["FechaIniVenta"] . " 00:00:00");
        $minima = DateTime::createFromFormat("d/m/Y H:i:s", "01/01/$anio 00:00:00");
        if ($fechaIni < $minima) {
            $errores["FechaIniVenta"] = "La fecha de inicio no puede ser anterior al 01/01/$anio";
        }
    } else {
        $errores["FechaIniVenta"] = "La fecha de inicio no es valida (dd/mm/aaaa)";
    }

    // fecha de fin de venta
    $partes = [];
    if (preg_match("/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/", $datos["FechaFinVenta"], $partes)
        && checkdate(intval($partes[2]), intval($partes[1]), intval($partes[3]))) {
        $datos["FechaFinVenta"] = sprintf("%02d/%02d/%04d", $partes[1], $partes[2], $partes[3]);
        $fechaFin = DateTime::createFromFormat("d/m/Y H:i:s", $datos["FechaFinVenta"] . " 00:00:00");
        if ($fechaIni !== null && $fechaFin < $fechaIni) {
            $errores["FechaFinVenta"] = "La fecha de fin no puede ser anterior a la de inicio";
        }
    } else {
        $errores["FechaFinVenta"] = "La fecha de fin no es valida (dd/mm/aaaa)";
    }

    // precio (se admite la coma decimal)
    $datos["Precio"] = str_replace(",", ".", $datos["Precio"]);
    if (!is_numeric($datos["Precio"]) || floatval($datos["Precio"]) < 30) {
        $errores["Precio"] = "El precio debe ser un numero mayor o igual que 30";
    }

    // propiedades adicionales segun el tipo de mueble
    if (in_array("PorcentajeReciclado", $propiedades)) {
        if (!preg_match("/^\d{1,3}$/", $datos["PorcentajeReciclado"])
            || intval($datos["PorcentajeReciclado"]) > 100) {
            $errores["PorcentajeReciclado"] = "El porcentaje debe ser un entero entre 0 y 100";
        }
    }

    if (in_array("Peso", $propiedades)) {
        $datos["Peso"] = str_replace(",", ".", $datos["Peso"]);
        if (!is_numeric($datos["Peso"]) || floatval($datos["Peso"]) < 15 || floatval($datos["Peso"]) > 300) {
            $errores["Peso"] = "El peso debe estar entre 15 y 300";
        }
    }

    if (in_array("Serie", $propiedades) && $datos["Serie"] == "") {
        $errores["Serie"] = "La serie no puede estar vacia";
    }

    /*
     * Si todo es correcto se actualiza el mueble.
     * El orden del array (anio antes que las fechas) es el que necesitan los set.
     */
    if (!$errores) {
        foreach ($propiedades as $prop) {
            $valor = $datos[$prop];
            if (isset($tipos[$prop])) {
                $valor = $tipos[$prop] == "int" ? intval($valor) : floatval($valor);
            }
            $metodo = "set" . $prop;
            $mueble->$metodo($valor);
        }
        $correcto = true;
    }
}

//dibuja la plantilla de la vista
inicioCabecera("TIENDA - Modificar");
cabecera();
finCabecera();
inicioCuerpo("TIENDA DE MUEBLES - Modificar mueble", $barra);
cuerpo($id, $mueble, $propiedades, $datos, $errores, $etiquetas, $correcto);  //llamo a la vista
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
function cuerpo(int $id, MuebleBase $mueble, array $propiedades, array $datos,
    array $errores, array $etiquetas, bool $correcto)
{
?>
    <main id="main">
        <h2>Mueble <?= $id ?> (<?= get_class($mueble) ?>)</h2>

        <?php
        if ($correcto) {
            mostrarResumen($mueble);
        } else {
            formulario($id, $propiedades, $datos, $errores, $etiquetas);
        }
        ?>

        <br>
        <a href="/aplicacion/principal/index.php">Ir a index.php</a>
    </main>
<?php
}

/**
 * Formulario de modificacion. Recibe los valores a mostrar y los errores.
 */
function formulario(int $id, array $propiedades, array $datos, array $errores, array $etiquetas): void
{
    if (isset($errores["general"])) {
        echo '<div class="error">' . htmlspecialchars($errores["general"]) . "</div>\n";
    }
?>
    <form action="modificar.php" method="post">
        <input type="hidden" name="mueble" value="<?= $id ?>">
        <fieldset>
            <legend>Datos del mueble</legend>
            <?php foreach ($propiedades as $prop) { ?>
                <label for="<?= $prop ?>"><?= $etiquetas[$prop] ?? $prop ?></label>
                <input type="text" name="<?= $prop ?>" id="<?= $prop ?>"
                    value="<?= htmlspecialchars($datos[$prop]) ?>">
                <?php if (isset($errores[$prop])) { ?>
                    <div class="error"><?= htmlspecialchars($errores[$prop]) ?></div>
                <?php } ?>
                <br>
            <?php } ?>
        </fieldset>
        <br>
        <input type="submit" name="guardar" value="Guardar cambios">
    </form>
<?php
}

/**
 * Resumen de los datos recogidos (ya guardados en el mueble)
 */
function mostrarResumen(MuebleBase $mueble): void
{
    $propiedades = array_merge(
        ["Nombre", "Fabricante", "Pais", "Anio", "FechaIniVenta", "FechaFinVenta", "Precio"],
        $mueble->damePropiedadesAdicionales()
    );
?>
    <div class="exito">El mueble se ha modificado correctamente.</div>
    <div class="resultado">
        <?php foreach ($propiedades as $prop) {
            $metodo = "get" . $prop; ?>
            <strong><?= $prop ?>:</strong> <?= htmlspecialchars(strval($mueble->$metodo())) ?><br>
        <?php } ?>
        <br><strong>Caracteristicas:</strong> <?= htmlspecialchars(strval($mueble->getCaracteristicas())) ?>
    </div>
    <p>Ojo: como no usamos sesiones, al volver a index.php el mueble aparece con sus datos originales.</p>
<?php
}
