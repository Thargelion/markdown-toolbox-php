<?php

namespace app\lib;

/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 10/04/17
 * Time: 12:57
 *
 * use app\lib\Collection;
 * use app\lib\KeyInvalidException;
 * use app\lib\Section;
 */

include_once __DIR__ . '/Cortador.php';
include_once __DIR__ . '/Collection.php';
include_once __DIR__ . '/CollectionFiller.php';
include_once __DIR__ . '/Section.php';
include_once __DIR__ . '/MarkdownUtilities.php';

class Generador
{
    private $textoIngresado = '';
    private $posicionInicial = 0;
    private $arraySecciones = array();
    private $seccionAnterior = array();
    private $nivel = 0;
    private $nivelSiguiente = 1;
    private $nivelMD = "";
    private $id = 0;
    private $posicionFinal = 0;

    public function __construct(string $textoIngresado) //iniciador del generador loco
    {
        $this->setPosicionFinal($this->calcularFinalDelTexto($textoIngresado));
        $this->setPosicionInicial($this->ubicarPosicionInicialDelTexto($textoIngresado));
        $recorte = new Cortador($this->getPosicionInicial(), $this->getPosicionFinal(), $textoIngresado);
        $textoIngresado = PHP_EOL . $recorte->getTexto();
        $this->setTextoIngresado($textoIngresado);
        $this->setPosicionInicial($this->ubicarPosicionInicialDelTexto($textoIngresado));
        $this->setPosicionFinal($this->calcularFinalDelTexto($textoIngresado));
        $this->armadoEstructura();
        $this->ordenadoEstructura();
        var_dump($this->getArraySecciones());
    }

    private function calcularFinalDelTexto(string $materiaPrima): int
    {
        return strlen($materiaPrima);
    }

    private function ubicarPosicionInicialDelTexto($materiaPrima): int
    {
        return stripos($materiaPrima, '#');

    }

    private function armadoEstructura()
    {
        $secciones[][] = array();// Array con la cantidad de secciones que hay por nivellelel
        $seccionInicial = array( //Seccion madre de todas las secciones, la #0
            'id' => $this->getId(),
            'nivel' => 0,
            'titulo' => '',
            'texto' => $this->getTextoIngresado(),
            'posicionInicialSeccion' => $this->getPosicionInicial(),
            'posicionFinalSeccion' => $this->getPosicionFinal(),
            'posicionInicialTexto' => 0,
            'superior' => 0
        );
        $secciones[0][$seccionInicial['id']] = $seccionInicial;
        $this->setId(1);
        for ($i = 0; $i < 6; $i++) { //RECORRE NIVELES
            foreach ($secciones[$i] as $seccionMadre) { //RECORRE SECCIONES POR NIVEL
                $id = $this->getId();
                $nivelSiguiente = $this->getNivelSiguiente();
                $hijasACargar = $this->seccionesInternas($nivelSiguiente, PHP_EOL . $seccionMadre['titulo'] . $seccionMadre['texto'], $id, $seccionMadre['id']);
                if ($secciones[$nivelSiguiente]) { //determina si hay hay una sección cargada en el nivel siguiente para no "sumar" un array nulo
                    $merge = $secciones[$nivelSiguiente] + $hijasACargar;
                    $secciones[$nivelSiguiente] = $merge;
                } else {
                    $secciones[$nivelSiguiente] = $hijasACargar;
                }
            }
            $this->setNivel($this->getNivel() + 1);
            $this->setNivelSiguiente($this->getNivelSiguiente() + 1);
        }
        $this->setArraySecciones($secciones);
    }

    // Función que devuelve un array con todas las secciones internas de una sección. Para instanciar secciones, toma la información de la sección madre
    private function seccionesInternas(int $nivelABuscar, string $materiaPrima, int $id, int $madre)
    {
        $puntero = 0;
        $seccionesHijas = array();
        $posicionFinal = strlen($materiaPrima);
        $cantidadDeSeccionesInternas = $this->contador($nivelABuscar, $materiaPrima);
        for ($i = 0; $i < $cantidadDeSeccionesInternas; $i++) {
            $recorteDeSeccion = new Cortador($puntero, $posicionFinal, $materiaPrima);
            $subSeccion = $recorteDeSeccion->getTexto();
            $seccionACargar = new Section($subSeccion, $nivelABuscar, $id, $madre);
            $puntero = $puntero + $seccionACargar->getPosicionFinalSeccion();
            $seccionesHijas[$id] = $seccionACargar->devolucionArray();
            $id++;
        }
        $this->setId($id);
        return $seccionesHijas;
    }

    private function contador(int $nivelActual, string $materiaPrima): int
    {
        $MDTools = new MarkdownUtilities();
        $elementoABuscar = $MDTools->construccionNivelHeaderMD($nivelActual);
        $this->setNivelMD($elementoABuscar);
        return substr_count($materiaPrima, $elementoABuscar);
    }

    private function ordenadoEstructura()
    {
        function ordenaElementosPorId($a, $b)
        {
            if ($a['id'] > $b['id']) {
                return 1;
            } else {
                return 0;
            }
        }

        function ordenaElementosPorMadre($a, $b)
        {
            if ($a['superior'] > $b['superior']) {
                return 1;
            } else {
                return 0;
            }
        }

        $secciones = $this->getArraySecciones();
        $meltSecciones = $secciones[0]; // unifico los uniersos del array =0
        for ($i = 1; $i < 6; $i++) {
                $meltSecciones = $secciones[$i] + $meltSecciones;
        }
        usort($meltSecciones, "ordenaElementosPorId");
        usort($meltSecciones, "ordenaElementosPorMadre");
        $this->setArraySecciones($meltSecciones);
    }

    /**
     * @return string
     */
    public function getTextoIngresado(): string
    {
        return $this->textoIngresado;
    }

    /**
     * @param string $texto
     */
    public function setTextoIngresado(string $texto)
    {
        $this->textoIngresado = $texto;
    }

    /**
     * @return int
     */
    public function getPosicionInicial(): int
    {
        return $this->posicionInicial;
    }

    /**
     * @param int $posicionInicial
     */
    public function setPosicionInicial(int $posicionInicial)
    {
        $this->posicionInicial = $posicionInicial;
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
     * @return array
     */
    public function getArraySecciones(): array
    {
        return $this->arraySecciones;
    }

    /**
     * @param array $arraySecciones
     */
    public function setArraySecciones(array $arraySecciones)
    {
        $this->arraySecciones = $arraySecciones;
    }

    /**
     * @return array
     */
    public function getSeccionAnterior(): array
    {
        return $this->seccionAnterior;
    }

    /**
     * @param array $seccionAnterior
     */
    public function setSeccionAnterior(array $seccionAnterior)
    {
        $this->seccionAnterior = $seccionAnterior;
    }

    /**
     * @return int
     */
    public function getPosicionFinal(): int
    {
        return $this->posicionFinal;
    }

    /**
     * @param int $posicionFinal
     */
    public function setPosicionFinal(int $posicionFinal)
    {
        $this->posicionFinal = $posicionFinal;
    }


    /**
     * @return int
     */
    public function getNivel(): int
    {
        return $this->nivel;
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
    public function getNivelSiguiente(): int
    {
        return $this->nivelSiguiente;
    }

    /**
     * @param int $nivelSiguiente
     */
    public function setNivelSiguiente(int $nivelSiguiente)
    {
        $this->nivelSiguiente = $nivelSiguiente;
    }


}