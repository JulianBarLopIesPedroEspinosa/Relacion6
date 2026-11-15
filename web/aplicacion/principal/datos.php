<?php
/*
 * datos.php
 * Datos de la aplicacion. Como aun no guardamos datos de forma permanente,
 * se define un array indexado (posiciones a partir de 1) con 5 muebles.
 * Cada mueble tiene al menos dos caracteristicas ademas de las basicas.
 *
 * Se incluye desde cabecera.php, asi el array esta definido en un unico punto
 * y accesible en todas las paginas.
 *
 * Orden de los parametros del constructor:
 * nombre, fabricante, pais, fechaIniVenta, fechaFinVenta, anio, precio, (adicionales)
 */

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
