<?php
/*
 * Pagina inicial del sitio.
 * Redirige automaticamente a la pagina principal de la aplicacion.
 * header() tiene que ir antes de cualquier salida (echo, HTML...) y
 * exit evita que se siga ejecutando el resto del fichero.
 */
header("Location: /aplicacion/principal/index.php");
exit;
