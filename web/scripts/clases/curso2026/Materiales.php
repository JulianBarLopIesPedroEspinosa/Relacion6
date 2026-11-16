<?php
enum Materiales: int
{
    case Madera = 5;
    case Hiero = 10;
    case Plastico = 15;
    case Piedra = 20;


    function describete(): string
    {
        return $this->name;
    }
}
