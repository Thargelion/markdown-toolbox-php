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
    private $seccionAnterior = array();
    private $posicionFinal = 0;
    private $numSeccion = 0;
    private $nivel = 1;

    public function __construct($textoIngresado) //iniciador del generador loco
    {
        $this->setTextoIngresado($textoIngresado);
        $this->setPosicionFinal($this->getFinalDelTexto());
        $this->armadoEstructura();
    }

    private function getFinalDelTexto(): int
    {
        return strlen($this->getTextoIngresado()) - 1;
    }

    private function armadoEstructura()
    {
        $validador = 1;
        $posicion = $this->getPosicion();
        $posicionFinal = $this->getPosicionFinal();
        $arrayACargar = array();
        $nivel = 1;
        $id = 1;
        $madre = 0;
        while ($validador) {
            while ($posicion < $posicionFinal) {
                $seccionAnterior = $this->getSeccionAnterior();
                if (!$seccionAnterior) {
                    $seccionACargar = new Section($this->getTextoIngresado(), $nivel, $posicion, $id, $madre);
                } else {
                    $seccionACargar = new Section($this->getTextoIngresado(), $nivel, $posicion, $id, $seccionAnterior['id']);
                }
                $arrayACargar[$seccionACargar->getId()] = $seccionACargar->devolucionArray();
                $id++;
                $this->setSeccionAnterior($arrayACargar);
            }
        }
        $this->setArraySecciones($arrayACargar);
    }

    private function ordenadoEstructura()
    {
        function ordenaElementos($a, $b)
        {
            if ($a['id'] > $b['id']) {
                return 1;
            } else {
                return 0;
            }
        }

        $secciones = $this->getArraySecciones();
        usort($secciones, "ordenaElementos");
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
    public function getPosicion(): int
    {
        return $this->posicion;
    }

    /**
     * @param int $posicion
     */
    public function setPosicion(int $posicion)
    {
        $this->posicion = $posicion;
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
    public function getNumSeccion(): int
    {
        return $this->numSeccion;
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

}