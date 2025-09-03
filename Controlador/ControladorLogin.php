<?php

class LoginController
{
    public function isLogged()
    {

        if (isset($_SESSION["logged"]) &&  $_SESSION["logged"] == true) {


            // Decomentar Despues :


            
            // if (isset($_SESSION['user-datos']) && $_SESSION['user-datos'] == false && $_SESSION['menu-item'] !== "Perfil" && $_SESSION['menu-item'] !== "Salir") {
            //     $_SESSION['menu-item'] = "Perfil";
            //     header('refresh:0');
            // }
            
            if (!isset($_SESSION['menu-item'])) {
                $_SESSION['menu-item'] = "Dashboard";
                header('refresh:0');
            }


        } else {

            if (!isset($_SESSION['menu-item'])) {
                $_SESSION['menu-item'] = "Inicio";
                header('refresh:0');
            }

            // require_once('Vista/home.php');

            return false;
        }
    }

    public function logIn($email, $contraseña)
    {
        if (isset($_POST[md5('action')]) && $_POST[md5('action')] == md5('login') && isset($_POST['email']) && isset($_POST['password'])) {

            include_once('Modelo/ModeloUsuario.php');
            $usuarioModel = new ModeloUsuario();
            $usuario = $usuarioModel->consultarUsuario($email);

            if (!$usuario || $usuario['email'] != $email || $usuario['contraseña'] != md5($contraseña)) {
                return false; // No se encontró el usuario
            }

            $_SESSION['user-id'] = $usuario['id'];
            $_SESSION['user-datos'] = $usuario['datos_completos'];
            $_SESSION["logged"] = true;

            // var_dump($usuario);

            return true; // Simula que el usuario se ha logueado correctamente

        } else {
            return false; // No se pudo iniciar sesión
        }
    }

    public function logOut()
    {
        unset($_SESSION["logged"]);
        unset($_SESSION['user-id']);
        unset($_SESSION['user-datos']);
        session_destroy();
    }
}
