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
include_once __DIR__ . '/MarkdownTools.php';

class Generador
{
    private $textoIngresado = '';
    private $posicionInicial = 0;
    private $arraySecciones = array();
    private $nivel = 0;
    private $nivelInferior = 1;
    private $nivelMD = "";

    public function __construct(string $textoIngresado) //iniciador del generador loco
    {
        $this->setPosicionFinal($this->calcularFinalDelTexto($textoIngresado));
        $this->setPosicionInicial($this->ubicarPosicionInicialDelTexto($textoIngresado));
        $recorte = new Cortador($this->getPosicionInicial(), $this->getPosicionFinal(), $textoIngresado);
        $textoIngresado = $recorte->getTexto();
        $this->setTextoIngresado($textoIngresado);
        $this->armadoEstructura();
        //      $this->ordenadoEstructura();
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
        $id = 0;
        $seccionesPorNivel[][] = array();// Array con la cantidad de secciones que hay por nivellelel
        $seccionMadre = array( //Seccion madre de todas las secciones, la #0
            'id' => 0,
            'nivel' => 0,
            'titulo' => '',
            'texto' => $this->getTextoIngresado(),
            'posicionInicialSeccion' => $this->getPosicionInicial(),
            'posicionFinalSeccion' => $this->getPosicionFinal(),
            'superior' => 0,
            'esMadre' => 1
        );
        $seccionesPorNivel[0][$seccionMadre['id']] = $seccionMadre;
        $seccionesPorNivel[0][1] = $seccionMadre;
        for ($i = 0; $i < 6; $i++) {
            echo "I: " . $i . "</br>";
            echo "Nivel: " . $this->getNivel() . "</br>";
            foreach ($seccionesPorNivel[$i] as $seccion) {
                var_dump($seccionesPorNivel[$i]);
                echo "I ATRODEN: " . $i . "</br>";
                $idNuevo = $id + 1;
                echo "Texto ID " . $seccion['id'] . " " . $seccion['texto'];
                var_dump($seccion);
                $seccionesPorNivel[$this->getNivelInferior()] = $this->seccionesInternas($i, $seccion['texto'], $idNuevo, $seccion[$id]['posicionInicialSeccion'], $seccion[$id]['id']);
                $id = $idNuevo;
            }
            var_dump($seccionesPorNivel[$this->getNivelInferior()]);
            $ultimaSeccion = end($seccionesPorNivel[$this->getNivelInferior()]);
            echo "ULTIMA SECCION A CARGARELELE: " . "</br>";
            $id = $ultimaSeccion['id'];
            $this->setNivel($this->getNivel() + 1);
            $this->setNivelInferior($this->getNivelInferior() + 1);
        }
        $this->setArraySecciones($seccionesPorNivel);
    }

    // Función que devuelve un array con todas las secciones internas de una sección. Para instanciar secciones, toma la información de la sección madre
    private function seccionesInternas($nivel, $materiaPrima, $id, $posicionInicial, $madre)
    {
        $seccionesACargar = array();
        $cantidadDeSeccionesInternas = $this->contador($nivel + 1, $materiaPrima); // +1 ya que busco secciones de un nivel más alto
        for ($i = 0; $i < $cantidadDeSeccionesInternas; $i++) {
            $seccionACargar = new Section($materiaPrima, $nivel + 1, $posicionInicial, $id, $madre);
            $seccionesACargar[$id] = $seccionACargar->devolucionArray();
            $id++;
        }
        return $seccionesACargar;
    }

    private function construccionNivelHeaderMD($nivel) //construye el nivel del header md en base al nivel de la sección
    {
        //       echo "Nivel recibido: " . $nivel . "</br>";
        $nivelHeaderMD = "";
        for ($i = 0; $i < $nivel; $i++) {
            $nivelHeaderMD = $nivelHeaderMD . "#";
        }
        return PHP_EOL . $nivelHeaderMD . " ";
    }

    private function contador(int $nivelActual, string $materiaPrima): int
    {
        $MDTools = new MarkdownTools();
        $elementoABuscar = $MDTools->construccionNivelHeaderMD($nivelActual);
        $this->setNivelMD($elementoABuscar);
        return substr_count($materiaPrima, $elementoABuscar);
    }
    /*
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
            usort($secciones, "ordenaElementosPorId");
            usort($secciones, "ordenaElementosPorMadre");
            $this->setArraySecciones($secciones);
        }
    */
    /**
     * @return string
     */
    public
    function getTextoIngresado(): string
    {
        return $this->textoIngresado;
    }

    /**
     * @param string $texto
     */
    public
    function setTextoIngresado(string $texto)
    {
        $this->textoIngresado = $texto;
    }

    /**
     * @return int
     */
    public
    function getPosicionInicial(): int
    {
        return $this->posicionInicial;
    }

    /**
     * @param int $posicionInicial
     */
    public
    function setPosicionInicial(int $posicionInicial)
    {
        $this->posicionInicial = $posicionInicial;
    }

    /**
     * @return array
     */
    public
    function getArraySecciones(): array
    {
        return $this->arraySecciones;
    }

    /**
     * @param array $arraySecciones
     */
    public
    function setArraySecciones(array $arraySecciones)
    {
        $this->arraySecciones = $arraySecciones;
    }

    /**
     * @return array
     */
    public
    function getSeccionAnterior(): array
    {
        return $this->seccionAnterior;
    }

    /**
     * @param array $seccionAnterior
     */
    public
    function setSeccionAnterior(array $seccionAnterior)
    {
        $this->seccionAnterior = $seccionAnterior;
    }

    /**
     * @return int
     */
    public
    function getPosicionFinal(): int
    {
        return $this->posicionFinal;
    }

    /**
     * @param int $posicionFinal
     */
    public
    function setPosicionFinal(int $posicionFinal)
    {
        $this->posicionFinal = $posicionFinal;
    }


    /**
     * @return int
     */
    public
    function getNumSeccion(): int
    {
        return $this->numSeccion;
    }

    /**
     * @return int
     */
    public
    function getNivel(): int
    {
        return $this->nivel;
    }

    /**
     * @param int $nivel
     */
    public
    function setNivel(int $nivel)
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
    public function getNivelInferior(): int
    {
        return $this->nivelInferior;
    }

    /**
     * @param int $nivelInferior
     */
    public function setNivelInferior(int $nivelInferior)
    {
        $this->nivelInferior = $nivelInferior;
    }

}