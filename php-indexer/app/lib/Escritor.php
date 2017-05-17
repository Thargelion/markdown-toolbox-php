<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 21/04/17
 * Time: 10:02
 */

namespace app\lib;

/*use app\lib\Collection;
use app\lib\Generador;
*/
include_once __DIR__ . '/CollectionFiller.php';
include_once __DIR__ . '/Generador.php';

class Escritor
{
    private $materiaPrima = "";
    private $texto = "";
    private $indice = array();
    private $titulos = "";

    public function __construct($texto)
    {
        $estructura = new Generador($texto);
        $secciones = $estructura->getArraySecciones();
        $this->generaIndice($secciones);
    }

    private function generaIndice($materiaPrima)
    {

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

    /**
     * @return string
     */
    public function getIndice(): array
    {
        return $this->indice;
    }

    /**
     * @param string $indice
     */
    public function setIndice(array $indice)
    {
        $this->indice = $indice;
    }

    /**
     * @return string
     */
    public function getTitulos(): string
    {
        return $this->titulos;
    }

    /**
     * @param string $titulos
     */
    public function setTitulos(string $titulos)
    {
        $this->titulos = $titulos;
    }

}