<?php

namespace app\lib;

/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 10/04/17
 * Time: 12:57
 */
use app\lib\Collection;
use app\lib\KeyInvalidException;
use app\lib\Section;


include_once __DIR__ . '/Lector.php';
include_once __DIR__ . '/Collection.php';
include_once __DIR__ . '/CollectionFiller.php';
include_once __DIR__ . '/Section.php';

class Generador
{
    private $textoIngresado = '';
    private $posicion = 0;
    public $arraySecciones = array();
    public $arrayHeaders = array();
    private $coleccionSecciones;
    private $numSeccion = 0;
    private $nivel = 1;

    public function __construct($textoIngresado) //iniciador del generador loco
    {
        $this->setTextoIngresado($textoIngresado);
        $this->armadoEstructura();
    }

    private function armadoEstructura()
    {
        $coleSecciones = new Collection();
        if($this->getPosicion()){
            $pos = $this->getPosicion();
        }else{
            $pos = 0;
        }
        $textoIngresado = $this->getTextoIngresado();
        $nivel = $this->getNivel();
        $finalDelTexto = $this->getFinalDelTexto();
        $i = 0;
        while ($pos < $finalDelTexto) {
            echo "Ubicacion: " . $pos . "</br>";
            echo "Final del texto: " . $finalDelTexto . "</br>";
            $coleSecciones->addItem(new Section($textoIngresado, $nivel, $pos, $i));
            echo "Texto Titulo: " . $coleSecciones->getItem($i)->getTitulo() . "</br>";
            try {
                $pos = $coleSecciones->getItem($i)->getUbicacion();
            } catch (KeyInvalidException $e) {
                print "El while de generador no encuentra secciones! x.x";
            }
            if($coleSecciones->getItem($i)->getPosSubNivel())
            {
                $coleSecciones->addItem(new Section($textoIngresado, $nivel + 1, $coleSecciones->getItem($i)->getPosSubNivel(), $i));
            }else{
                break;
            }
            $i++;
            echo "Ubicacion luego de cargar: " . $pos . "</br>";
            if($i > 6)
            {
                echo "KABUNCHE x.x";
                die;
            }
        } //añade las secciones hasta que no haya más lugar para recorrer
        $this->setPosicion($pos); //completa la posición en la que se encuentra el armador en base a la posición final de la sección cargada
        $this->setColeccionSecciones($coleSecciones); //llena la colección con las secciones armadas
    }

    private function getFinalDelTexto()
    {
        $materiaPrima = $this->getTextoIngresado();
        return strripos($materiaPrima, PHP_EOL);
    }

    public function setNumSeccion(int $numSeccion)
    {
        $this->numSeccion = $numSeccion;
    }


    public function fillTexto($POST)
    {
        $this->textoIngresado = $POST;
    }

    private function nextIteration(string $aBuscar)
    {
        return stripos($this->getTextoIngresado(), $aBuscar, $this->getPosicion());
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
     * @return int
     */
    public function getNumSeccion(): int
    {
        return $this->numSeccion;
    }

    /**
     * @param int $numSeccion
     */

    /**
     * @return array
     */
    public function getArrayHeaders(): array
    {
        return $this->arrayHeaders;
    }

    /**
     * @param array $arrayHeaders
     */
    public function setArrayHeaders(array $arrayHeaders)
    {
        $this->arrayHeaders = $arrayHeaders;
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
     * @return mixed
     */
    public function getColeccionSecciones()
    {
        return $this->coleccionSecciones;
    }

    /**
     * @param mixed $coleccionSecciones
     */
    public function setColeccionSecciones($coleccionSecciones)
    {
        $this->coleccionSecciones = $coleccionSecciones;
    }


}