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

include_once __DIR__ . '/Lector.php';
include_once __DIR__ . '/Collection.php';
include_once __DIR__ . '/CollectionFiller.php';
include_once __DIR__ . '/Section.php';

class Generador
{
    private $textoIngresado = '';
    private $posicion = 0;
    private $arraySecciones = array();
    private $nivel = 1;

    public function __construct($textoIngresado, $posicion) //iniciador del generador loco
    {
        $this->setTextoIngresado($textoIngresado);
        $this->setPosicionFinal($this->getFinalDelTexto());
        $this->armadoEstructura();
        //      $this->ordenadoEstructura();
    }

    private function getFinalDelTexto(): int
    {
        return strlen($this->getTextoIngresado());
    }

    private function armadoEstructura()
    {
        $nivel = 0;
        $id = 0;
        $posicionInicial = $this->getPosicion();
        $arrayDeSeccionesACargar = array(); //INICIADOR ARRAY
        $seccionAnterior = array(); //Sección previamente cargada, para usar de referencia
        $seccionesPorNivel = array();
        $seccionesPorNivel[$nivel] = array();// Array con la cantidad de secciones que hay por nivellelel
        $seccionInicial = array(
            'id' => 0,
            'nivel' => 0,
            'titulo' => '',
            'posicionFinalSeccion' => $this->getPosicionFinal(),
            'superior' => 0,
            'esMadre' => 1
        );
        $materiaPrima = $this->getTextoIngresado();
        foreach ($seccionesPorNivel[$nivel] as $seccion) {
            $cantidadDeSeccionesInternas = $this->contador($nivel + 1, $materiaPrima); // +1 ya que busco secciones de un nivel más alto
            for ($i = 0; $i < $cantidadDeSeccionesInternas; $i++) {
                $id++;
                $seccionACargar = new Section($materiaPrima, $nivel + 1, $posicionInicial, $id, $seccionesPorNivel[$nivel][$id]);
            }
        }

    }


    public function construccionNivelHeaderMD($nivel) //construye el nivel del header md en base al nivel de la sección
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
        $elementoABuscar = $this->construccionNivelHeaderMD($nivelActual);
        return substr_count($materiaPrima, $elementoABuscar);
    }

    private function buscadorItineranciaSiguiente($posicionInicial, $elementoABuscar): int
    {
        return stripos($this->getMateriaPrima(), $elementoABuscar, $posicionInicial);
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
        usort($secciones, "ordenaElementosPorId");
        usort($secciones, "ordenaElementosPorMadre");
        $this->setArraySecciones($secciones);
    }

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
    function getPosicion(): int
    {
        return $this->posicion;
    }

    /**
     * @param int $posicion
     */
    public
    function setPosicion(int $posicion)
    {
        $this->posicion = $posicion;
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

}