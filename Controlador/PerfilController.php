<?php

class PerfilController extends BaseController
{
    private $modeloFeligres;
    private $modeloUsuario;

    public function __construct()
    {
        $this->modeloFeligres = new ModeloFeligres();
        $this->modeloUsuario  = new ModeloUsuario();
    }

    // ================= MOSTRAR PERFIL =================

    public function mostrar()
    {
        $this->requiereAutenticacion(true);

        $feligres = $this->modeloFeligres
            ->mdlObtenerPorUsuarioId($_SESSION['user-id']);

        include_once __DIR__ . '/../Vista/datos-personales.php';
    }

    // ================= ACTUALIZAR PERFIL =================

    public function actualizar()
    {
        try {

            $this->requiereAutenticacion(true);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->mostrar();
                return;
            }

            // ================= CAPTURAR DATOS =================

            $datosFeligres = [
                'idUser'            => $_SESSION['user-id'],
                'tipo-doc'          => $_POST['tipoDocumento'] ?? null,
                'documento'         => trim($_POST['numeroDocumento'] ?? ''),
                'primer-nombre'     => trim($_POST['primerNombre'] ?? ''),
                'segundo-nombre'    => trim($_POST['segundoNombre'] ?? ''),
                'primer-apellido'   => trim($_POST['primerApellido'] ?? ''),
                'segundo-apellido'  => trim($_POST['segundoApellido'] ?? ''),
                'fecha-nacimiento'  => $_POST['fechaNacimiento'] ?? null,
                'telefono'          => trim($_POST['telefono'] ?? ''),
                'direccion'         => trim($_POST['direccion'] ?? ''),
            ];

            // ================= VALIDAR CAMPOS REQUERIDOS =================

            if (
                empty($datosFeligres['tipo-doc']) ||
                empty($datosFeligres['documento']) ||
                empty($datosFeligres['primer-nombre']) ||
                empty($datosFeligres['primer-apellido']) ||
                empty($datosFeligres['fecha-nacimiento']) ||
                empty($datosFeligres['direccion'])
            ) {
                $_SESSION['error'] = 'Completa todos los campos requeridos.';
                $this->mostrar();
                return;
            }

            // ================= VALIDAR SOLO LETRAS =================

            $regexSoloLetras = '/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u';

            // Primer nombre
            if (!preg_match($regexSoloLetras, $datosFeligres['primer-nombre'])) {
                $_SESSION['error'] = 'El primer nombre solo puede contener letras.';
                $this->mostrar();
                return;
            }

            // Segundo nombre (opcional)
            if (!empty($datosFeligres['segundo-nombre']) &&
                !preg_match($regexSoloLetras, $datosFeligres['segundo-nombre'])) {
                $_SESSION['error'] = 'El segundo nombre solo puede contener letras.';
                $this->mostrar();
                return;
            }

            // Primer apellido
            if (!preg_match($regexSoloLetras, $datosFeligres['primer-apellido'])) {
                $_SESSION['error'] = 'El primer apellido solo puede contener letras.';
                $this->mostrar();
                return;
            }

            // Segundo apellido (opcional)
            if (!empty($datosFeligres['segundo-apellido']) &&
                !preg_match($regexSoloLetras, $datosFeligres['segundo-apellido'])) {
                $_SESSION['error'] = 'El segundo apellido solo puede contener letras.';
                $this->mostrar();
                return;
            }

            // ================= VALIDAR TELÉFONO =================

            if (!empty($datosFeligres['telefono'])) {

                if (!ctype_digit($datosFeligres['telefono'])) {
                    $_SESSION['error'] = 'El teléfono solo puede contener números.';
                    $this->mostrar();
                    return;
                }

                if (strlen($datosFeligres['telefono']) < 7) {
                    $_SESSION['error'] = 'El teléfono debe tener mínimo 7 dígitos.';
                    $this->mostrar();
                    return;
                }
            }

            // ================= VALIDAR FECHA =================

            $fechaNacimiento = $datosFeligres['fecha-nacimiento'];
            $fechaActual     = date('Y-m-d');
            $fechaMinima     = '1900-01-01';

            if ($fechaNacimiento > $fechaActual) {
                $_SESSION['error'] = 'La fecha no puede ser futura.';
                $this->mostrar();
                return;
            }

            if ($fechaNacimiento < $fechaMinima) {
                $_SESSION['error'] = 'La fecha no es válida.';
                $this->mostrar();
                return;
            }

            $edad = date_diff(
                date_create($fechaNacimiento),
                date_create($fechaActual)
            )->y;

            if ($edad > 120) {
                $_SESSION['error'] = 'La fecha no es válida.';
                $this->mostrar();
                return;
            }

            // ================= GUARDAR =================

            $feligresExistente = $this->modeloFeligres
                ->mdlObtenerPorUsuarioId($_SESSION['user-id']);

            if ($feligresExistente) {
                $datosFeligres['id'] = $feligresExistente['id'];
                $status = $this->modeloFeligres
                    ->mdlUpdateFeligres($datosFeligres);
            } else {
                $status = $this->modeloFeligres
                    ->mdlCrearFeligres($datosFeligres);
            }

            if ($status['status'] === 'error') {
                $_SESSION['error'] = $status['message'];
                $this->mostrar();
                return;
            }

            // ================= ACTUALIZAR USUARIO =================

            $this->modeloUsuario
                ->mdlMarcarDatosCompletos($_SESSION['user-id']);

            $_SESSION['user-datos'] = true;
            $_SESSION['success']    = 'Datos actualizados correctamente.';

            header('Location: ?route=dashboard');
            exit();

        } catch (Exception $e) {

            $_SESSION['error'] = 'Error al actualizar tus datos.';
            $this->mostrar();
        }
    }
}