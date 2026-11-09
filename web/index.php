<?php
include_once(dirname(__FILE__) . "/cabecera.php");
//Controlador

$barra = [

    [
        "TEXTO" => "inicio",
        "ENLACE" => "index.php"
    ]
];


//dibuja la plantilla de la vista
inicioCabecera("Tienda");
cabecera();
finCabecera();
inicioCuerpo("TIENDA", $barra);
cuerpo();  //llamo a la vista
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
function cuerpo()
{
?>
    <main id="main">
        <br><br>
        <a href="">TO DO </a> <br>
<?php
}
