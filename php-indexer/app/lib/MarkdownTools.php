<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 04/05/17
 * Time: 16:01
 */

namespace app\lib;


class MarkdownTools
{
    public function construccionNivelHeaderMD(int $nivel): string //construye el nivel del header md en base al nivel de la sección
    {
        //       echo "Nivel recibido: " . $nivel . "</br>";
        $nivelHeaderMD = "";
        for ($i = 0; $i < $nivel; $i++) {
            $nivelHeaderMD = $nivelHeaderMD . "#";
        }
        return PHP_EOL . $nivelHeaderMD . " ";
    }
}