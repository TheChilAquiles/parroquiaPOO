<?php
ob_start();
session_start();



// -- LINEA PARA TESTEAR EN LOG --
file_put_contents(__DIR__ . '/logs/RusbelApp.log', 'Entro a Mostrar noticias controlador ' . "\n", FILE_APPEND);
// -- LINEA PARA TESTEAR EN LOG  --






if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['menu-item'])) {
  $_SESSION['menu-item'] = $_POST['menu-item'];
}

include_once('Controlador/ControladorLogin.php');
$Login = new LoginController();
$Login->isLogged();

// if ($Login->isLogged()) {

//  if (isset($_SESSION['user-datos']) && $_SESSION['user-datos'] == false && $_SESSION['menu-item'] !== "Perfil" && $_SESSION['menu-item'] !== "Salir") {
//  	$_SESSION['menu-item'] = "Perfil";
//  	header('refresh:0');
//  }

//  if (!isset($_SESSION['menu-item'])) {
//  	$_SESSION['menu-item'] = "Dashboard";
//  	header('refresh:0');
//  }


// } else {
//  if (!isset($_SESSION['menu-item'])) {
//  	$_SESSION['menu-item'] = "Inicio";
//  	header('refresh:0');
//  }
// };

echo "Session" . $_SESSION['menu-item'];

include_once('Vista/componentes/plantillaTop.php');




switch ($_SESSION['menu-item']) {
  case 'Inicio':
    include_once('Vista/home.php');
    break;

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

      echo $_POST['email'];
      include_once('Vista/registroCompleto.php');
    } else {
      include_once('Vista/register.php');
    };

    break;


  case 'Perfil':



    echo $_SESSION['user-datos'];

    include_once('Vista/datos-personales.php');



    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (isset($_POST[md5('action')]) && $_POST[md5('action')] == md5('Buscar')) {

        if (!isset($_POST['tipoDocumento']) && !isset($_POST['numeroDocumento'])) {
          return false;
        }

        var_dump($_POST);

        include_once('Controlador/ControladorFeligres.php');
        $Feligres = new FeligresController();
        $feligres = $Feligres->ctrlConsularFeligres($_POST['tipoDocumento'], $_POST['numeroDocumento']);

        if ($feligres['status'] == 'error') {

          echo $feligres['error'];


          echo "<script>errorFeligres()</script>";
        } else {

          $_SESSION['user-datos'] = $feligres;


          echo "<script>DatosFeligres(" . $feligres . ")</script>";
        }
      }

      if (isset($_POST[md5('action')]) && $_POST[md5('action')] == md5('Añadir')) {


        include_once('Controlador/ControladorFeligres.php');
        $Feligres = new FeligresController();

        $datosFeligres = [
          'idUser' => $_SESSION['user-id'],
          'tipo-doc' => $_POST['tipoDocumento'],
          'documento' => $_POST['numeroDocumento'],
          'primer-nombre' =>   $_POST['primerNombre'],
          'segundo-nombre' => $_POST['segundoNombre'] ? $_POST['segundoNombre'] : '',
          'primer-apellido' => $_POST['primerApellido'],
          'segundo-apellido' => $_POST['segundoApellido'] ? $_POST['segundoApellido'] : '',
          'fecha-nacimiento' => $_POST['fechaNacimiento'],
          'telefono' => $_POST['telefono'] ? $_POST['telefono'] : '',
          'direccion' => $_POST['direccion'],
        ];

        if ($_SESSION['user-datos'] == false) {

          $status = $Feligres->ctrlCrearFeligres($datosFeligres);
        } else {
          $status = $Feligres->ctrlActualizarFeligres($datosFeligres);
        }



        if ($status['status'] == 'error') {
          $error = $status['error'];
          echo $error;
        } else {
          $_SESSION['user-datos'] = true; // Actualizar la sesión para indicar que los datos del usuario están completos
          $_SESSION['menu-item'] = "Dashboard";
          header('refresh:0');

          $message = $status['message'];
        }


        var_dump($_POST);
      }



      echo "hola desde el post";
    }



    if (isset($_SESSION['user-datos']) && $_SESSION['user-datos'] == false) {
      echo "<script>mostarBaner()</script>";
    }

    break;

  case 'Libros':


    if (isset($_POST[md5('action')]) && $_POST[md5('action')] == md5('DefinirTipolibro')) {


      if (isset($_POST[md5('tipo')])) {

        $tipo = "";

        switch ($_POST[md5('tipo')]) {

          case md5('Bautizos'):
            $libroTipo = 'Bautizos';
            $tipo = 1;
            break;


          case md5('Confirmaciones'):
            $libroTipo = 'Confirmaciones';
            $tipo = 2;
            break;

          case md5('Defunciones'):
            $libroTipo = 'Defunciones';
            $tipo = 3;
            break;


          case md5('Matrimonios'):
            $libroTipo = 'Matrimonios';
            $tipo = 4;
            break;
        }




        include_once('Controlador/ControladorLibro.php');

        $controllerLibro = new LibroController();

        $cantidad = $controllerLibro->ctrlConsultarCantidadLibros($tipo);



        if (isset($_POST[md5('sub-action')]) && $_POST[md5('sub-action')] ==   md5('NuevoLibro')) {

          $controllerLibro->ctrlCrearLibro($tipo, $cantidad);
          $cantidad = $controllerLibro->ctrlConsultarCantidadLibros($tipo);
        }


        if (isset($_POST[md5('sub-action')]) && $_POST[md5('sub-action')] ==   md5('RegistrosLibro')) {

          echo "sisas :D";

          include_once('Vista/sacramentos.php');
        } else {

          include_once('Vista/libros.php');
        }
      } else {
        include_once('Vista/libros-tipo.php');
      }
    } else {
      include_once('Vista/libros-tipo.php');
    }
    break;

  case 'Contacto':
    include_once('Vista/contacto.php');
    break;

  case 'Informacion':
    include_once('Vista/informacion.php');
    break;

  case 'Noticias':

    // -- LINEA PARA TESTEAR EN LOG --
    file_put_contents(__DIR__ . '/logs/SamuelApp.log', __DIR__ . '/Controlador/ControladorNoticia.php' . "\n", FILE_APPEND);
    // -- LINEA PARA TESTEAR EN LOG  --



    try {
      require_once(__DIR__ . '/Controlador/ControladorNoticia.php');
      $noticiaController = new ControladorNoticia();
      $noticiaController->ctrGestionarNoticias();

      file_put_contents(__DIR__ . '/logs/SamuelApp.log', 'All fine' . "\n", FILE_APPEND);
    } catch (\Throwable $th) {

      // -- LINEA PARA TESTEAR EN LOG --
      file_put_contents(__DIR__ . '/logs/SamuelApp.log', 'AaaaaaaaaH ! : ' . $th->getMessage() . "\n", FILE_APPEND);
      // -- LINEA PARA TESTEAR EN LOG  --
    }


    break;

  case 'Grupos':
    // Llama al controlador de grupos y al método que gestiona todo
    require_once(__DIR__ . '/Controlador/ControladorGrupo.php');
    $grupoController = new GrupoController();
    $grupoController->ctrlGestionarGrupos();
    break;

    

    case 'Pagos':
      include_once('Vista/crear_pago.php');
    break;




  case 'Reportes':
    include_once(__DIR__ . '/Vista/reportes.php');

    break;
  case 'Dashboard':
    include_once('Vista/dashboard.php');
    break;






    // $_POST['']

    // include_once('Vista/libros-tipo.php');


}







// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['menu-item'])) {
//  $_SESSION['menu-item'] = $_POST['menu-item'];
// } else {
// }



echo "Session" . $_SESSION['menu-item'];






// if ($_SERVER["REQUEST_METHOD"] == "POST" )) 	{

//  // echo "Post".$_POST['menu-item'] ;
//  echo "Session" . $_SESSION['menu-item'];

//  switch ($_SESSION['menu-item'] ) {

//  	case 'Salir':
//  	  include_once('Controlador/ControladorLogin.php');
//  	  $Login = new LoginController();
//  	  $Login->logOut();
//  	  header('refresh:0');
//  	  break;

//  	case 'Login':


//  	  if (isset($_POST[md5('action')]) && $_POST[md5('action')] == md5('login') && isset($_POST['email']) && isset($_POST['password'])) {
//  	  	include_once('Controlador/ControladorLogin.php');
//  	  	$Login = new LoginController();
//  	  	$logear = $Login->logIn($_POST['email'], $_POST['password']);
//  	  	if ($logear) {
//  	  	  $_SESSION['menu-item'] = "Dashboard";
//  	  	  header('refresh:0');
//  	  	} else {
//  	  	  include_once('Vista/login.php');
//  	  	  echo "<script>errorLogin()</script>";
//  	  	  exit();
//  	  	}



//  	  	// $_SESSION['menu-item'] = "Dashboard";
//  	  	// header('refresh:0');

//  	  } else {
//  	  	include_once('Vista/login.php');
//  	  }

//  	  break;

//  	case 'Registro':

//  	  if (isset($_POST[md5('action')]) && $_POST[md5('action')] == md5('registro') && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password-confirm'])) {

//  	  	$datosUsuario = [
//  	  	  'email' => $_POST['email'],
//  	  	  'password' => $_POST['password'],
//  	  	  'password-confirm' => $_POST['password-confirm']
//  	  	];

//  	  	include_once('Controlador/ControladorUsuario.php');
//  	  	$usuario = new UsuarioController();
//  	  	$status = $usuario->ctrlCrearUsuario($datosUsuario);

//  	  	if ($status['status'] == 'error') {
//  	  	  $error = $status['error'];
//  	  	  include_once('Vista/register.php');
//  	  	  echo "<script>existEmail()</script>";
//  	  	  exit();
//  	  	} else {
//  	  	  $message = $status['message'];
//  	  	}


//  	  	// include_once('Controlador/ControladorLogin.php');
//  	  	// $Login = new LoginController();
//  	  	// $Login->register($_POST['email'], $_POST['password'], $_POST['password-confirm']);
//  	  	// $_SESSION['menu-item'] = "Dashboard";
//  	  	// header('refresh:0');

//  	  	echo $_POST['email'];
//  	  	include_once('Vista/registroCompleto.php');
//  	  } else {
//  	  	include_once('Vista/register.php');
//  	  };

//  	  break;

//  	case 'Inicio':

//  	  header('refresh:0');
//  	  break;

//  	case 'Dashboard':
//  	  echo "Hola Desde el casw2";
//  	  break;

//  	case 'Perfil':

//  	  break;


//  	default:
//  	  # code...
//  	  break;
//  }
// } else {

//  echo "sesion else" . $_SESSION['menu-item'];

//  include_once('Controlador/ControladorLogin.php');
//  $Login = new LoginController();
//  $Login->isLogged();
// }





include_once('Vista/componentes/plantillaBottom.php');
