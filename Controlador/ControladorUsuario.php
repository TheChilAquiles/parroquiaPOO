<?php

class UsuarioController
{
    public function __construct()
    {
        
            require_once __DIR__ . '/../Modelo/ModeloUsuario.php';
    }



    public function ctrlCrearUsuario($usuario)
    {
        // Validar los datos del usuario
        if (empty($usuario['email']) || empty($usuario['password']) || empty($usuario['password-confirm'])) {
            return "Todos los campos son obligatorios.";
        }
        if ($usuario['password'] !== $usuario['password-confirm']) {
            return "Las contraseñas no coinciden.";
        }

        // Crear una instancia del modelo de usuario
        $usuarioModel = new ModeloUsuario();
        // Registrar el usuario
        $resultado = $usuarioModel->mdlRegistrarUsuario($usuario);

        if ($resultado && $resultado['status'] === 'error') {
            return ['status' => 'error', 'error' => $resultado['message']]; // El email ya existe
        } else {
            return ['status' => 'success', 'message' => "Usuario registrado correctamente"]; // Usuario registrado correctamente
        }
    }


    


    public function obtenerUsuarios()
    {
        $usuarioModel = new Usuario();
        return $usuarioModel->obtenerUsuarios();
    }

    public function obtenerUsuarioPorId($id)
    {
        $usuarioModel = new Usuario();
        return $usuarioModel->obtenerUsuarioPorId($id);
    }

    
}
