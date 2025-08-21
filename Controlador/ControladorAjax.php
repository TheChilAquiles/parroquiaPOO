<?php
include_once '../models/conexion.php';
include_once '../models/records.php';

$database = new conexion();
$db = $database->conectar();

$record = new records($db);

if(!empty($_POST['Doaction']) && $_POST['Doaction'] == 'listRecords') {
    $record->listRecords();
}
if(!empty($_POST['Doaction']) && $_POST['Doaction'] == 'addRecord') {   
    $record->name = $_POST["name"];
    $record->parroquia = $_POST["parroquia"];
    $record->fechaFallecimiento = $_POST["fechaFallecimiento"];
    $record->lugarNacimiento = $_POST["lugarNacimiento"];
    $record->age = $_POST["age"];
    $record->causaMuerte = $_POST["causaMuerte"];
    $record->hijoDe = $_POST["hijoDe"];
    $record->estadoCivil = $_POST["estadoCivil"];
    $record->addRecord();
}
if(!empty($_POST['Doaction']) && $_POST['Doaction'] == 'getRecord') {
    $record->id = $_POST["id"];
    $record->getRecord();
}
if(!empty($_POST['Doaction']) && $_POST['Doaction'] == 'updateRecord') {
    $record->id = $_POST["id"];
    $record->name = $_POST["name"];
    $record->parroquia = $_POST["parroquia"];
    $record->fechaFallecimiento = $_POST["fechaFallecimiento"];
    $record->lugarNacimiento = $_POST["lugarNacimiento"];
    $record->age = $_POST["age"];
    $record->causaMuerte = $_POST["causaMuerte"];
    $record->hijoDe = $_POST["hijoDe"];
    $record->estadoCivil = $_POST["estadoCivil"];
    $record->updateRecord();
}
if(!empty($_POST['Doaction']) && $_POST['Doaction'] == 'deleteRecord') {
    $record->id = $_POST["id"];
    $record->deleteRecord();
}
?>