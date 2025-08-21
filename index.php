<?php
ob_start();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $_SESSION['menu-item'] = $_POST['menu-item'];
}

include_once('Vista/componentes/plantillaTop.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // echo "Post".$_POST['menu-item'] ;
  // echo "Session" . $_SESSION['menu-item']; 

  switch ($_SESSION['menu-item']) {

    case 'Salir':
      include_once('Controlador/ControladorLogin.php');
      $Login = new LoginControler();
      $Login->logOut();

      header('refresh:0');
      break;
    case 'Login':
      include_once('Controlador/ControladorLogin.php');
      $Login = new LoginControler();
      $Login->logIn('User', 'Password');
      $_SESSION['menu-item'] = "Dashboard";
      header('refresh:0');
      break;

    case 'Registro':

      if (isset($_POST['register'])) {
        
      } else {
        include_once('Vista/register.php');
      };


      break;


    case 'Inicio':

      header('refresh:0');
      break;
    case 'Dashboard':
      header('refresh:0');
      break;


    default:
      # code...
      break;
  }
} else {

  echo "sesion else" . $_SESSION['menu-item'];

  include_once('Controlador/ControladorLogin.php');
  $Login = new LoginControler();
  $Login->isLogged();
}





include_once('Vista/componentes/plantillaBottom.php');
