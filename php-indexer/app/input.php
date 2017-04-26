<?php

/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 10/04/17
 * Time: 12:43
 */
class Test{
    public $valor1;
    public $valor2;
    public $valorTuVieja;
    public $lvl;
    public $id;
}

$uno = new Test();
$uno->valor1 = "CACA 1";
$uno->valor2 = "PAPA 1";
$uno->valorTuVieja = "TV1";
$uno->lvl = 1;
$uno->id = 1;
$unodos = new Test();
$unodos->valor1 = "CACA 12";
$unodos->valor2 = "PAPA 12";
$unodos->valorTuVieja = "TV12";
$unodos->lvl = 2;
$unodos->id = 1;
$tres = new Test();
$tres->valor1 = "CACA 3";
$tres->valor2 = "PAPA 3";
$tres->valorTuVieja = "TV3";
$tres->lvl = 1;
$tres->id = 2;

$arrayLoco = array();

$arrayACargar = array(
    'valor1' => $uno->valor1,
    'valor2' => $uno->valor2,
    'valorTuVieja' => $uno->valorTuVieja,
    'lvl' => $uno->lvl,
    'id' => $uno->id
);
array_push($arrayLoco, $arrayACargar);

$arrayACargar = array(
    'valor1' => $tres->valor1,
    'valor2' => $tres->valor2,
    'valorTuVieja' => $tres->valorTuVieja,
    'lvl' => $tres->lvl,
    'id' => $tres->id
);
array_push($arrayLoco, $arrayACargar);
$arrayACargar = array(
    'valor1' => $unodos->valor1,
    'valor2' => $unodos->valor2,
    'valorTuVieja' => $unodos->valorTuVieja,
    'lvl' => $unodos->lvl,
    'id' => $unodos->id
);

array_push($arrayLoco, $arrayACargar);
echo "<pre>";
print_r($arrayLoco);
echo "</pre>";
foreach ($arrayLoco as $index) {
    if($index['id'] === 1)
    {
        echo "lele" . "</br>";
    }
}

function compararerlgue($a, $b)
{
    if($a['id'] > $b['id'])
    {
        return 1;
    }else{
        return 0;
    }
}

usort($arrayLoco, compararerlgue);

echo "<pre>";
print_r($arrayLoco);
echo "</pre>";