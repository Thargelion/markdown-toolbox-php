<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 21/04/17
 * Time: 12:10
 */

namespace app\lib;

include_once __DIR__ . '/Collection.php';


class CollectionFiller extends Collection
{
    function __construct($array)
    {
        $this->items = $array;
    }
}