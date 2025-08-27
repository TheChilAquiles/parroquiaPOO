<?php

include_once '../Modelo/Conexion.php';
include_once '../Modelo/ModeloSacramento.php';
include_once('../Controlador/ControladorFeligres.php');



// $database = new conexion();
// $db = $database->conectar();

$archivo = 'logs/app.log';
file_put_contents($archivo, "Numero" . $_POST['Numero'], FILE_APPEND);



 file_put_contents($archivo, $_POST['Numero'] , FILE_APPEND);
 try {
   $feligresController = new FeligresController();

 } catch (\Throwable $th) {
  //  throw $th;
   file_put_contents('logs/app.log', $th->getMessage(), FILE_APPEND);
 }




$record = new ModeloSacramento($_POST['Tipo'], $_POST['Numero']);





if (!empty($_POST['Doaction'])) {

  switch ($_POST['Doaction']) {
    case 'listRecords':
      return $record->listRecords();
      break;

    case 'addRecord':

      $archivo = 'logs/app.log'; // Carpeta logs/ debe existir o se crea
      file_put_contents($archivo, 'setetado', FILE_APPEND);
      return $record->addRecords();
      break;


    case 'buscarUsuario':

      $numeroDoc = $_POST['numeroDoc'];
      $tipoDoc = $_POST['tipoDoc'];

      $feligres = $feligresController->ctrlConsularFeligres($tipoDoc, $numeroDoc);
    

      // $archivo = 'logs/app.log'; // Carpeta logs/ debe existir o se crea
      // file_put_contents($archivo, 'Tipo doc' . $tipoDoc  . 'buscarUsuario: ' . $numeroDoc . "\n", FILE_APPEND);
      // file_put_contents($archivo, 'feligres: ' . print_r($feligres['data'], true) . "\n", FILE_APPEND);
      
  
      echo json_encode($feligres);

      break;


    default:
      # code...
      break;
  }
}
