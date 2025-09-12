<?php
include_once __DIR__ . '/../Modelo/ModeloSacramento.php' ;
include_once __DIR__ . '/../Controlador/ControladorFeligres.php' ;
include_once __DIR__ . '/../Modelo/Conexion.php' ;


// $database = new conexion();
// $db = $database->conectar();

$archivo = 'logs/app.log';




$feligresController = new FeligresController();
$record = new ModeloSacramento($_POST['Tipo'], $_POST['Numero']);





if (!empty($_POST['Doaction'])) {

  switch ($_POST['Doaction']) {

    case 'listRecords':

      return $record->listRecords();
      break;

    case 'addRecord':

      $archivo = 'logs/app.log'; // Carpeta logs/ debe existir o se crea
      file_put_contents($archivo, 'add Record C-sacramento : ' . print_r($_POST, true), FILE_APPEND);
      return $record->CrearSacramento($_POST);
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
