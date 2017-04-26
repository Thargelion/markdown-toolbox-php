<?php

namespace app\lib;

//use app\lib\Lector;

/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 10/04/17
 * Time: 16:25
 * Nota: Se identifica una sección como cualquier subdivisión del texto mediante headers. Cada sección tendrá tantas subsecciones como subheaders contenga.
 */
class Section
{
    private $nivel = 1;
    private $titulo = "";
    private $texto = "";
    private $ubicacion = 0; //posición del plano donde se encuentra la sección a buscar
    private $materiaPrima = "";
    private $posicionFinalSeccion = 0;
    private $posicionFinalFinal;
    private $posSubNivel = 0;
    private $superior;
    private $id = 0;

    public function __construct($materiaPrima, $nivel, $ubicacion, $id)
    {
        $this->setPosSubNivel(0);
        $this->setId($id);
        $this->setPosicionFinalFinal(strripos($this->getMateriaPrima(), PHP_EOL)); //calculo la posición final en base a la primer instancia de \n posterior a la primer instancia de #
        $this->setUbicacion($ubicacion);
        $this->setMateriaPrima($materiaPrima);
        $this->setNivel($nivel);
        $this->autoCompletar();
    }

    private function autoCompletar()
    {
        $textoInicio = $this->construccionNivelHeaderMD(); //genero qué texto es mi inicio de la sección
        $tamIndice = strlen($textoInicio); //calculo el tamaño de # del índice
        $posicionInicialALeer = stripos($this->getMateriaPrima(), $textoInicio, $this->getUbicacion()); //calculo la posición inicial en base a la primer instancia del #
        $posicionFinalTitulo = stripos($this->getMateriaPrima(), PHP_EOL, $posicionInicialALeer);  //calculo dónde termina el título con el \n
        $posicionFinalSeccion = stripos($this->getMateriaPrima(), $textoInicio, $posicionFinalTitulo); //calculo la posición final en base a la primer instancia de \n posterior a la primer instancia de #
        $lectorTitulo = new Lector($posicionInicialALeer, $posicionFinalTitulo, $this->getMateriaPrima());
        $posicionInicialALeer = stripos($this->getMateriaPrima(), $textoInicio, $posicionFinalTitulo); //corro la posicion inicial para que el texto no incluya al título
        $lectorTexto = new Lector($posicionInicialALeer, $posicionFinalSeccion, $this->getMateriaPrima());
        $recorteTitulo = $lectorTitulo->getTexto();
        $recorteTexto = $lectorTexto->getTexto();
        $this->setTitulo($recorteTitulo);
        $this->setTexto($recorteTexto);
        $this->setPosSubNivel($this->buscadorInterno($posicionInicialALeer));
        $this->setUbicacion($posicionFinalSeccion);
        $this->setPosicionFinalSeccion($posicionFinalSeccion);
        if($this->getPosSubNivel() > 0)
        {
            $this->setSuperior($this->getId());
        }else{
            $this->setSuperior(0);
        }
    }

    private function construccionNivelHeaderMD(): string //construye el nivel del header md en base al nivel de la sección
    {
        $nivel = $this->getNivel();
        $nivelHeaderMD = "";
        for ($i = 0; $i < $nivel; $i++) {
            $nivelHeaderMD = $nivelHeaderMD . "#";
        }
        return $nivelHeaderMD . " ";
    }

    private function buscadorInterno($posicionInicial): int
    {
        $nivelABuscar = "#" . $this->construccionNivelHeaderMD();
        return stripos($this->getMateriaPrima(), $nivelABuscar, $posicionInicial);
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
    public function getUbicacion(): int
    {
        return $this->ubicacion;
    }

    /**
     * @param int $ubicacion
     */
    public function setUbicacion(int $ubicacion)
    {
        $this->ubicacion = $ubicacion;
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
     * @return int
     */
    public function getPosSubNivel(): int
    {
        return $this->posSubNivel;
    }

    /**
     * @param int $posSubNivel
     */
    public function setPosSubNivel(int $posSubNivel)
    {
        $this->posSubNivel = $posSubNivel;
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
     * @return mixed
     */
    public function getPosicionFinalFinal()
    {
        return $this->posicionFinalFinal;
    }

    /**
     * @param mixed $posicionFinalFinal
     */
    public function setPosicionFinalFinal($posicionFinalFinal)
    {
        $this->posicionFinalFinal = $posicionFinalFinal;
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

}