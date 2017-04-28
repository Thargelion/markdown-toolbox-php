<?php

namespace app\lib;

//use app\lib\Lector;

include_once __DIR__ . "/Lector.php";

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
    private $ubicacionInicial = 0; //posición del plano donde se encuentra la sección a buscar
    private $materiaPrima = "";
    private $posicionFinalSeccion = 0;
    private $superior = 0;
    private $id = 0;
    private $nivelHeaderMD = "";
    private $hayHijo = 0;

    public function __construct(string $materiaPrima, int $nivel, int $ubicacion, int $id, int $madre)
    {
        $this->setSuperior($madre);
        $this->setId($id);
        $this->setUbicacionInicial($ubicacion);
        $this->setMateriaPrima($materiaPrima);
        $this->setNivel($nivel);
        $this->setNivelHeaderMD($this->construccionNivelHeaderMD($nivel));
        $this->autoCompletar();
        $this->setPosicionFinalSeccion($this->buscadorFinalSeccion());
    }

    private function autoCompletar()
    {
        echo "Titulen: " . "</br>";
        $this->setTitulo($this->completarTitulo());
        echo "Texten: " . "</br>";
        $this->setTexto($this->completarTexto());
        $this->hayHijo();
    }

    private function completarTitulo(): string
    {
        $posicionFinal = $this->buscadorItineranciaSiguiente($this->getUbicacionInicial(), PHP_EOL);
        //       echo "Posicion inicial título: " . $this->getUbicacionInicial() . "</br>";
        //       echo "Posicion final título: " . $posicionFinal . "</br>";
        $recorte = new Lector($this->getUbicacionInicial(), $posicionFinal - 1, $this->getMateriaPrima());
        $this->setUbicacionInicial($posicionFinal);
        return $recorte->getTexto();
    }

    private function completarTexto(): string
    {
        $posicionInicial = $this->getUbicacionInicial() + strlen($this->getNivelHeaderMD());
        $posicionFinal = $this->buscadorItineranciaSiguiente($posicionInicial, $this->getNivelHeaderMD());
        $recorte = new Lector($posicionInicial, $posicionFinal, $this->getMateriaPrima());
        return $recorte->getTexto();
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

    private function buscadorItineranciaSiguiente($posicionInicial, $elementoABuscar): int
    {
        return stripos($this->getMateriaPrima(), $elementoABuscar, $posicionInicial);
    }

    private function buscadorFinalSeccion(): int
    {
        $inicio = $this->getUbicacionInicial();
        $tituloMD = $this->getNivelHeaderMD();
        $tamTituloMD = strlen($tituloMD);
        echo "Inicio en seccion(): " . $inicio . "</br>";
        echo "Nivel a Cargar: " . $tituloMD . "</br>";
        $buscaFinal = $this->buscadorItineranciaSiguiente($inicio + $tamTituloMD, $tituloMD);
        echo "BuscaFinal: " . $buscaFinal . "</br>";
        if ($buscaFinal) {
            return $buscaFinal;
        } else {
            return strlen($this->getMateriaPrima());
        }
    }

    private function hayHijo()
    {
        $hijoABuscar = $this->construccionNivelHeaderMD($this->getNivel() + 1);
        echo $hijoABuscar;
        if (stripos($this->getTexto(), $hijoABuscar)) {
            $this->setHayHijo(1);
        } else {
            $this->setHayHijo(0);
        }
    }

    public function devolucionArray(): array
    {
        return array(
            'id' => $this->getId(),
            'nivel' => $this->getNivel(),
            'titulo' => $this->getTitulo(),
            'texto' => $this->getTexto(),
            'posicionFinalSeccion' => $this->getPosicionFinalSeccion(),
            'superior' => $this->getSuperior(),
            'hayHijo' => $this->getHayHijo()
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
    public function getUbicacionInicial(): int
    {
        return $this->ubicacionInicial;
    }

    /**
     * @param int $ubicacionInicial
     */
    public function setUbicacionInicial(int $ubicacionInicial)
    {
        $this->ubicacionInicial = $ubicacionInicial;
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
    public function getNivelHeaderMD(): string
    {
        return $this->nivelHeaderMD;
    }

    /**
     * @param string $nivelHeaderMD
     */
    public function setNivelHeaderMD(string $nivelHeaderMD)
    {
        $this->nivelHeaderMD = $nivelHeaderMD;
    }

    /**
     * @return int
     */
    public function getHayHijo(): int
    {
        return $this->hayHijo;
    }

    /**
     * @param int $hayHijo
     */
    public function setHayHijo(int $hayHijo)
    {
        $this->hayHijo = $hayHijo;
    }

}