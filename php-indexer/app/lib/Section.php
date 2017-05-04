<?php

namespace app\lib;

//use app\lib\Lector;

include_once __DIR__ . '/Cortador.php';
include_once __DIR__ . '/MarkdownTools.php';

/*
 * User: maximiliano
 * Date: 10/04/17
 * Time: 16:25
 * Nota: Se identifica una sección como cualquier subdivisión del texto mediante headers. Cada sección tendrá tantas subsecciones como subheaders contenga.
 */

class Section
{
    private $nivel = 0;
    private $titulo = "";
    private $texto = "";
    private $materiaPrima = "";
    private $posicionInicialSeccion = 0; //posición del plano donde se encuentra la sección a buscar
    private $posicionFinalSeccion = 0;
    private $superior = 0;
    private $id = 0;
    private $nivelMD = "";
    private $esMadre = 0;

    public function __construct(string $materiaPrima, int $nivel, int $ubicacion, int $id, int $madre)
    {
        $this->setSuperior($madre);
        $this->setId($id);
        $this->setPosicionInicialSeccion($ubicacion);
        $this->setMateriaPrima($materiaPrima);
        $this->setNivel($nivel);
        $MDTools = new MarkdownTools();
        $this->setNivelMD($MDTools->construccionNivelHeaderMD($nivel));
        $this->autoCompletar();
        $this->setPosicionFinalSeccion($this->buscadorFinalSeccion());
    }

    private function autoCompletar()
    {
        $this->setTitulo($this->completarTitulo());
        $this->setTexto($this->completarTexto());
    }

    private function completarTitulo(): string
    {
        $posicionFinal = $this->buscadorItineranciaSiguiente($this->getPosicionInicialSeccion(), PHP_EOL);
        $recorte = new Cortador($this->getPosicionInicialSeccion(), $posicionFinal, $this->getMateriaPrima());
        $this->setPosicionInicialSeccion($posicionFinal);
        return $recorte->getTexto();
    }

    private function completarTexto(): string
    {
        $posicionInicial = $this->getPosicionInicialSeccion() + strlen($this->getNivelMD());
        $posicionFinal = $this->buscadorItineranciaSiguiente($posicionInicial, $this->getNivelMD());
        $recorte = new Cortador($posicionInicial, $posicionFinal, $this->getMateriaPrima());
        return $recorte->getTexto();
    }

    private function buscadorItineranciaSiguiente($posicionInicial, $elementoABuscar): int
    {
        return stripos($this->getMateriaPrima(), $elementoABuscar, $posicionInicial);
    }


    private function buscadorFinalSeccion(): int
    {
        $inicio = $this->getPosicionInicialSeccion();
        $tituloMD = $this->getNivelMD();
        $tamTituloMD = strlen($tituloMD);
        echo "TITULOMD: " . $tituloMD . "</br>";
        echo "TA TITULO EME DE: " . $tamTituloMD . "</br>";
        $buscaFinal = $this->buscadorItineranciaSiguiente($inicio + $tamTituloMD, $tituloMD);
        echo "BUSCA FINAL: " . $buscaFinal . "</br>";
        if ($buscaFinal) {
            return $buscaFinal;
        } else {
            return strlen($this->getMateriaPrima());
        }
    }

    public function devolucionArray(): array
    {
        return array(
            'id' => $this->getId(),
            'nivel' => $this->getNivel(),
            'titulo' => $this->getTitulo(),
            'texto' => $this->getTexto(),
            'posicionInicialSeccion' => $this->getPosicionInicialSeccion(),
            'posicionFinalSeccion' => $this->getPosicionFinalSeccion(),
            'superior' => $this->getSuperior(),
            'esMadre' => $this->getEsMadre()
        );
    }



    /**
     * @return int
     */
    public function getNivel(): int
    {
        return $this->nivel;
    }

    /**
     * @return string
     */
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo(string $titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @param int $nivel
     */
    public function setNivel(int $nivel)
    {
        $this->nivel = $nivel;
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
     * @return int
     */
    public function getPosicionInicialSeccion(): int
    {
        return $this->posicionInicialSeccion;
    }

    /**
     * @param int $posicionInicialSeccion
     */
    public function setPosicionInicialSeccion(int $posicionInicialSeccion)
    {
        $this->posicionInicialSeccion = $posicionInicialSeccion;
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
     * @return int
     */
    public function getPosicionFinalSeccion(): int
    {
        return $this->posicionFinalSeccion;
    }

    /**
     * @param int $posicionFinalSeccion
     */
    public function setPosicionFinalSeccion(int $posicionFinalSeccion)
    {
        $this->posicionFinalSeccion = $posicionFinalSeccion;
    }


    /**
     * @return mixed
     */
    public function getSuperior()
    {
        return $this->superior;
    }

    /**
     * @param mixed $superior
     */
    public function setSuperior($superior)
    {
        $this->superior = $superior;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNivelMD(): string
    {
        return $this->nivelMD;
    }

    /**
     * @param string $nivelMD
     */
    public function setNivelMD(string $nivelMD)
    {
        $this->nivelMD = $nivelMD;
    }

    /**
     * @return int
     */
    public function getEsMadre(): int
    {
        return $this->esMadre;
    }

    /**
     * @param int $esMadre
     */
    public function setEsMadre(int $esMadre)
    {
        $this->esMadre = $esMadre;
    }

}