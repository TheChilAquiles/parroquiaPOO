<?php
ob_start();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['menu-item'])) {
  $_SESSION['menu-item'] = $_POST['menu-item'];
}

// teste comit
// teste comit Rusbel
 
include_once('Vista/componentes/plantillaTop.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" ))  {

  // echo "Post".$_POST['menu-item'] ;
  echo "Session" . $_SESSION['menu-item'];

  switch ($_SESSION['menu-item'] ) {

    case 'Salir':
      include_once('Controlador/ControladorLogin.php');
      $Login = new LoginController();
      $Login->logOut();
      header('refresh:0');
      break;

    case 'Login':


      if (isset($_POST[md5('action')]) && $_POST[md5('action')] == md5('login') && isset($_POST['email']) && isset($_POST['password'])) {
        include_once('Controlador/ControladorLogin.php');
        $Login = new LoginController();
        $logear = $Login->logIn($_POST['email'], $_POST['password']);
        if ($logear) {
          $_SESSION['menu-item'] = "Dashboard";
          header('refresh:0');
        } else {
          include_once('Vista/login.php');
          echo "<script>errorLogin()</script>";
          exit();
        }



        // $_SESSION['menu-item'] = "Dashboard";
        // header('refresh:0');

      } else {
        include_once('Vista/login.php');
      }

      break;

    case 'Registro':

      if (isset($_POST[md5('action')]) && $_POST[md5('action')] == md5('registro') && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password-confirm'])) {

        $datosUsuario = [
          'email' => $_POST['email'],
          'password' => $_POST['password'],
          'password-confirm' => $_POST['password-confirm']
        ];

        include_once('Controlador/ControladorUsuario.php');
        $usuario = new UsuarioController();
        $status = $usuario->ctrlCrearUsuario($datosUsuario);

        if ($status['status'] == 'error') {
          $error = $status['error'];
          include_once('Vista/register.php');
          echo "<script>existEmail()</script>";
          exit();
        } else {
          $message = $status['message'];
        }


        // include_once('Controlador/ControladorLogin.php');
        // $Login = new LoginController();
        // $Login->register($_POST['email'], $_POST['password'], $_POST['password-confirm']);
        // $_SESSION['menu-item'] = "Dashboard";
        // header('refresh:0');

        echo $_POST['email'];
        include_once('Vista/registroCompleto.php');
      } else {
        include_once('Vista/register.php');
      };

      break;

    case 'Inicio':

      header('refresh:0');
      break;

    case 'Dashboard':
      echo "Hola Desde el casw2";
      break;

    case 'Perfil':

      break;


    default:
      # code...
      break;
  }
} else {

  echo "sesion else" . $_SESSION['menu-item'];

  include_once('Controlador/ControladorLogin.php');
  $Login = new LoginController();
  $Login->isLogged();
}





include_once('Vista/componentes/plantillaBottom.php');
