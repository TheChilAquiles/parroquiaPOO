<?php

include_once '../Modelo/Conexion.php';
include_once '../Modelo/ModeloSacramento.php';



// $database = new conexion();
// $db = $database->conectar();

  $archivo = 'logs/app.log';
file_put_contents($archivo, "Numero".$_POST['Numero'] , FILE_APPEND);



// file_put_contents($archivo, $_POST['Numero'] , FILE_APPEND);




$record = new ModeloSacramento($_POST['Tipo'], $_POST['Numero']);


if (!empty($_POST['Doaction']) && $_POST['Doaction'] == 'listRecords') {

    return $record->listRecords();
}



if (!empty($_POST['Doaction']) && $_POST['Doaction'] == 'addRecord') {


  
  $archivo = 'logs/app.log'; // Carpeta logs/ debe existir o se crea
  
  
  file_put_contents($archivo, 'setetado', FILE_APPEND);
  
  

    return $record->addRecords();

    // $record->sacramentoTipo = $_POST["Tipo"];
    // $record->numero = $_POST["Numero"];
    // $record->nombreTipo = $_POST["NombreTipo"];


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
