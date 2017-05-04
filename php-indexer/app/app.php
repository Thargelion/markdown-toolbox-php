<?php
namespace app;
//use app\lib\Escritor;
use app\lib\Generador;

include_once __DIR__ . "/lib/Generador.php";

/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 10/04/17
 * Time: 13:02
 * "Estas son mis armas. Que el favor de los dioses me conceda la gloria"
 */
if (empty($_POST)) {
    header('location:https://www.youtube.com/watch?v=oHg5SJYRHA0');
    exit();
}
$texto = $_POST['input'];
/*
$escritor = new Escritor($texto);
$textoAEscribir = $escritor->getTexto();
*/
$test = new Generador($texto);
$secciones = $test->getArraySecciones();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Texto generado</title>
</head>
<body>
<h1>TEXTO GENERADO</h1>
<?php var_dump($secciones);?>
<textarea readonly>
</textarea>
</body>
</html>


