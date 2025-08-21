<?php

class LoginController
{
    public function isLogged()
    {

        if (isset($_SESSION["logged"]) &&  $_SESSION["logged"] = true ) {

            if(!isset($_SESSION['menu-item'])){
                $_SESSION['menu-item'] = "Dashboard";
                header('refresh:0');
            }

            require_once('Vista/dashboard.php');


        } else {

             if(!isset($_SESSION['menu-item'])){
                $_SESSION['menu-item'] = "Inicio";
                header('refresh:0');
            }

            require_once('Vista/home.php');
        }
    }

    public function logIn($usuario, $contraseña)
    {
        $_SESSION["logged"] = true;
    }

    public function logOut()
    {

        Unset($_SESSION["logged"]);
        session_destroy();

    }
}
