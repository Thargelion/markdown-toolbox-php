<?php

namespace app;
include 'app.php';
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 10/04/17
 * Time: 12:43
 */
$texto = $_POST['input'];
$generacion = new Lector(0, 5, $texto);
