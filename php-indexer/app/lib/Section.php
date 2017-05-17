<?php

namespace app\lib;

//use app\lib\Lector;

include_once __DIR__ . '/Cortador.php';
include_once __DIR__ . '/MarkdownUtilities.php';

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
    private $posicionInicialTexto = 0;
    private $superior = 0;
    private $id = 0;
    private $nivelMD = "";
    private $esBoludo = 0;

    public function __construct(string $materiaPrima, int $nivel, int $id, int $madre)
    {
        $this->setSuperior($madre);
        $this->setId($id);
        $this->setPosicionInicialSeccion(0);
        $this->setMateriaPrima($materiaPrima);
        $this->setNivel($nivel);
        $MDTools = new MarkdownUtilities();
        $this->setNivelMD($MDTools->construccionNivelHeaderMD($nivel));
        $this->setPosicionFinalSeccion($this->buscadorFinalSeccion());
        $this->autoCompletar();
    }

    private function autoCompletar()
    {
        $this->setTitulo($this->completarTitulo());
        $this->setTexto($this->completarTexto());
    }

    private function completarTitulo(): string
    {
        $posicionInicial = $this->buscadorItineranciaSiguiente($this->getPosicionInicialSeccion(), $this->getNivelMD());
        $posicionFinal = $this->buscadorItineranciaSiguiente($posicionInicial + strlen($this->getNivelMD()), PHP_EOL);
        $this->setPosicionInicialTexto($posicionFinal + 1);
        $recorte = new Cortador($posicionInicial, $posicionFinal, $this->getMateriaPrima());
        return $recorte->getTexto();
    }

    private function completarTexto(): string
    {
        $posicionInicialTexto = $this->getPosicionInicialTexto();
        $tamMD = strlen($this->getNivelMD());
        $this->setPosicionInicialTexto($posicionInicialTexto);
        $posicionFinal = $this->buscadorItineranciaSiguiente($posicionInicialTexto + $tamMD, $this->getNivelMD());
        if (!$posicionFinal)
            $posicionFinal = strlen($this->getMateriaPrima());
        $this->setPosicionFinalSeccion($posicionFinal);
        $posicionFinal = $posicionFinal - strlen($this->getTitulo());
        $recorte = new Cortador($posicionInicialTexto, $posicionFinal, $this->getMateriaPrima());
        return $recorte->getTexto();
    }

    private function buscadorItineranciaSiguiente(int $posicionInicial, string $elementoABuscar): int
    {
        return stripos($this->getMateriaPrima(), $elementoABuscar, $posicionInicial);
    }


    private function buscadorFinalSeccion(): int
    {
        $inicio = $this->getPosicionInicialSeccion();
        $tituloMD = $this->getNivelMD();
        $tamTituloMD = strlen($tituloMD);
        $buscaFinal = $this->buscadorItineranciaSiguiente($inicio + $tamTituloMD, $tituloMD);
        if ($buscaFinal) {
            return $buscaFinal - 1;
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
            'posicionInicialTexto' => $this->getPosicionInicialTexto(),
            'superior' => $this->getSuperior()
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
    public function getPosicionInicialTexto(): int
    {
        return $this->posicionInicialTexto;
    }

    /**
     * @param int $posicionInicialTexto
     */
    public function setPosicionInicialTexto(int $posicionInicialTexto)
    {
        $this->posicionInicialTexto = $posicionInicialTexto;
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
    public function getEsBoludo(): int
    {
        return $this->esBoludo;
    }

    /**
     * @param int $esBoludo
     */
    public function setEsBoludo(int $esBoludo)
    {
        $this->esBoludo = $esBoludo;
    }

}