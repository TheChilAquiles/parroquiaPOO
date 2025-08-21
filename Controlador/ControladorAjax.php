<?php

$mensaje = "sisas";


$archivo = 'logs/app.log'; // Carpeta logs/ debe existir o se crea
$fecha = date("Y-m-d H:i:s");




if (!file_exists(dirname($archivo))) {
    mkdir(dirname($archivo), 0755, true);
}


include_once '../Modelo/Conexion.php';
include_once '../Modelo/ModeloSacramento.php';



// $database = new conexion();
// $db = $database->conectar();


// file_put_contents($archivo, "mensaje q aa" , FILE_APPEND);


$record = new ModeloSacramento();


if (!empty($_POST['Doaction']) && $_POST['Doaction'] == 'listRecords') {
   return $record->listRecords();
}


// if (!empty($_POST['Doaction']) && $_POST['Doaction'] == 'addRecord') {
//     $record->name = $_POST["name"];
//     $record->parroquia = $_POST["parroquia"];
//     $record->fechaFallecimiento = $_POST["fechaFallecimiento"];
//     $record->lugarNacimiento = $_POST["lugarNacimiento"];
//     $record->age = $_POST["age"];
//     $record->causaMuerte = $_POST["causaMuerte"];
//     $record->hijoDe = $_POST["hijoDe"];
//     $record->estadoCivil = $_POST["estadoCivil"];
//     $record->addRecord();
// }
// if (!empty($_POST['Doaction']) && $_POST['Doaction'] == 'getRecord') {
//     $record->id = $_POST["id"];
//     $record->getRecord();
// }
// if (!empty($_POST['Doaction']) && $_POST['Doaction'] == 'updateRecord') {
//     $record->id = $_POST["id"];
//     $record->name = $_POST["name"];
//     $record->parroquia = $_POST["parroquia"];
//     $record->fechaFallecimiento = $_POST["fechaFallecimiento"];
//     $record->lugarNacimiento = $_POST["lugarNacimiento"];
//     $record->age = $_POST["age"];
//     $record->causaMuerte = $_POST["causaMuerte"];
//     $record->hijoDe = $_POST["hijoDe"];
//     $record->estadoCivil = $_POST["estadoCivil"];
//     $record->updateRecord();
// }
// if (!empty($_POST['Doaction']) && $_POST['Doaction'] == 'deleteRecord') {
//     $record->id = $_POST["id"];
//     $record->deleteRecord();
// }
