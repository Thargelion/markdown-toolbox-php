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
        //      $this->ordenadoEstructura();
    }

    private function getFinalDelTexto(): int
    {
        return strlen($this->getTextoIngresado()) - 1;
    }

    private function armadoEstructura()
    {
        echo "POSICION FINAL FINAL: " . $this->getPosicionFinal() . "</br>"; //DEBUG
        $validador = 1; //INICIADOR VALIDADOR
        $posicionFinal = $this->getPosicionFinal(); //POSICION DE INICIO DE LECTURA
        $arrayACargar = array(); //INICIADOR ARRAY
        $nivel = 1; //NIVEL INICIAL
        $id = 1; //ID INICIAL
        $madre = 0; // MADRE INICIAL
        do {
            $posicion = $this->getPosicion();
            echo $posicion;
            echo $posicionFinal;
            while ($posicion < $posicionFinal) {
                echo "<hr>";
                $seccionAnterior = $this->getSeccionAnterior();
                echo "Hay Hijo En el Anterior" .  $seccionAnterior['hayHijo'];
                echo "</br> ID Seccion Anterior" . $seccionAnterior['id'];
                if (array_key_exists('hayHijo', $seccionAnterior) && $seccionAnterior['hayHijo'] !== 0) {
                    $seccionACargar = new Section($this->getTextoIngresado(), $nivel, $posicion, $id, $seccionAnterior['id']);
                } else {
                    $seccionACargar = new Section($this->getTextoIngresado(), $nivel, $posicion, $id, $madre);
                }
                echo "TITULENGUE A Cargar: " . $seccionACargar->getTitulo() . "</br>";
                $arrayACargar[$seccionACargar->getId()] = $seccionACargar->devolucionArray();
                echo "<pre>";
                var_dump($arrayACargar[$seccionACargar->getId()]);
                echo "</pre>";
                echo "Superior: " . $arrayACargar['superior'] . "</br>";
                $id++;
                $this->setSeccionAnterior($seccionACargar->devolucionArray());
                $posicion = $seccionACargar->getPosicionFinalSeccion();
                if ($id > 10) {
                    echo "ID: X.X";
                    die;
                }
            }
            $nivel++;
            if (isset($seccionACargar)) {
                $validador = stripos($this->getTextoIngresado(), $seccionACargar->construccionNivelHeaderMD($nivel));
            }
            echo "Posicion: " . $posicion . "</br>";
            echo "Validador:" . $validador . "</br>";
            if ($nivel > 10) {
                echo "X.X";
                die;
            }
        } while ($validador);
        $this->setArraySecciones($arrayACargar);
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