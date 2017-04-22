<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 21/04/17
 * Time: 10:02
 */

namespace app\lib;

use app\lib\Collection;
use app\lib\Generador;

include_once __DIR__ . '/CollectionFiller.php';
include_once __DIR__ . '/Generador.php';

class Escritor
{
    private $materiaPrima = "";
    private $texto = "";

    public function __construct($texto)
    {
        $estructura = new Generador($texto);
        $secciones = $estructura->getColeccionSecciones();
        $cantSecciones = $secciones->length();
        $texto = "";
        for ($i = 0; $i < $cantSecciones; $i++) {
            try {
                $texto = $texto . $secciones->getItem($i)->getTitulo();
            } catch (KeyInvalidException $e) {
                echo "El for del Escritor no encuentra el índice x.x";
                break;
            }
        }
        $this->setTexto($texto);
    }

    /**
     * @return string
     */
    public function getMateriaPrima(): string
    {
        return $this->materiaPrima;
    }

    /**
     * @param string $materiaPrima
     */
    public function setMateriaPrima(string $materiaPrima)
    {
        $this->materiaPrima = $materiaPrima;
    }

    /**
     * @return string
     */
    public function getTexto(): string
    {
        return $this->texto;
    }

    /**
     * @param string $texto
     */
    public function setTexto(string $texto)
    {
        $this->texto = $texto;
    }

}