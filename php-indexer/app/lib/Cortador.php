<?php

namespace app\lib;
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 10/04/17
 * Time: 17:18
 */
class Cortador
{
    private $posInicial = 0;
    private $posFinal = 0;
    private $texto = "texto";

    // Carga un recorte de texto
    public function __construct($posInicial, $posFinal, $textoALeer)
    {
        $lenght = $posFinal - $posInicial;
        $this->setTexto(substr($textoALeer, $posInicial, $lenght)); //corta el texto en base a las posiciones cargadas
    }

    /**
     * @return int
     */
    public function getPosInicial()
    {
        return $this->posInicial;
    }

    /**
     * @param int $posInicial
     */
    public function setPosInicial(int $posInicial)
    {
        $this->posInicial = $posInicial;
    }

    /**
     * @return int
     */
    public function getPosFinal()
    {
        return $this->posFinal;
    }

    /**
     * @param int $posFinal
     */
    public function setPosFinal(int $posFinal)
    {
        $this->posFinal = $posFinal;
    }

    /**
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @param string $texto
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
    }

}