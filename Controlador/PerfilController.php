<?php
// ============================================================================
// PerfilController.php
// ============================================================================

class PerfilController extends BaseController
{
    private $modeloFeligres;
    private $modeloUsuario;

    public function __construct()
    {
        $this->modeloFeligres = new ModeloFeligres(); // ✅
        $this->modeloUsuario = new ModeloUsuario();
    }

    public function mostrar()
    {
        // Verificar autenticación pero permitir perfil incompleto
        $this->requiereAutenticacion(true);

        include_once __DIR__ . '/../Vista/datos-personales.php';
    }

    public function buscar()
    {
        // Verificar autenticación pero permitir perfil incompleto
        $this->requiereAutenticacion(true);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->mostrar();
            return;
        }

        $tipoDoc = $_POST['tipoDocumento'] ?? null;
        $numeroDoc = $_POST['numeroDocumento'] ?? null;

        if (empty($tipoDoc) || empty($numeroDoc)) {
            $_SESSION['error'] = 'Tipo y número de documento son requeridos.';
            $this->mostrar();
            return;
        }

        $feligres = $this->modeloFeligres->mdlConsultarFeligres($tipoDoc, $numeroDoc);

        if ($feligres['status'] === 'error') {
            $_SESSION['error'] = $feligres['error'];
            $this->mostrar();
            return;
        }

        $_SESSION['feligres_temporal'] = $feligres['data'];
        include_once __DIR__ . '/../Vista/datos-personales.php';
    }

    public function actualizar()
    {
        // Verificar autenticación pero permitir perfil incompleto
        $this->requiereAutenticacion(true);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->mostrar();
            return;
        }

        $datosFeligres = [
            'idUser' => $_SESSION['user-id'],
            'tipo-doc' => $_POST['tipoDocumento'] ?? null,
            'documento' => $_POST['numeroDocumento'] ?? null,
            'primer-nombre' => $_POST['primerNombre'] ?? null,
            'segundo-nombre' => $_POST['segundoNombre'] ?? '',
            'primer-apellido' => $_POST['primerApellido'] ?? null,
            'segundo-apellido' => $_POST['segundoApellido'] ?? '',
            'fecha-nacimiento' => $_POST['fechaNacimiento'] ?? null,
            'telefono' => $_POST['telefono'] ?? '',
            'direccion' => $_POST['direccion'] ?? null,
        ];

        // Validar campos requeridos
        if (empty($datosFeligres['tipo-doc']) || empty($datosFeligres['documento']) ||
            empty($datosFeligres['primer-nombre']) || empty($datosFeligres['primer-apellido']) ||
            empty($datosFeligres['fecha-nacimiento']) || empty($datosFeligres['direccion'])) {
            $_SESSION['error'] = 'Completa todos los campos requeridos.';
            $this->mostrar();
            return;
        }

        // Crear o actualizar feligres
        if ($_SESSION['user-datos'] == false) {
            $status = $this->modeloFeligres->mdlCrearFeligres($datosFeligres);
        } else {
            // Obtener ID del feligrés por usuario_id
            $feligres = $this->modeloFeligres->mdlConsultarFeligres($datosFeligres['tipo-doc'], $datosFeligres['documento']);
            if ($feligres) {
                $datosFeligres['id'] = $feligres['id'];
            }
            $status = $this->modeloFeligres->mdlUpdateFeligres($datosFeligres);
        }

        if ($status['status'] === 'error') {
            $_SESSION['error'] = $status['error'];
            $this->mostrar();
            return;
        }

        // Actualizar el campo datos_completos en la tabla usuarios
        $actualizado = $this->modeloUsuario->mdlMarcarDatosCompletos($_SESSION['user-id']);

        if ($actualizado) {
            $_SESSION['user-datos'] = true;
            $_SESSION['success'] = 'Datos actualizados correctamente.';
        } else {
            // Aun así marcamos la sesión como completa
            $_SESSION['user-datos'] = true;
            $_SESSION['success'] = 'Datos actualizados correctamente.';
            Logger::warning("No se pudo actualizar datos_completos en BD pero se marcó en sesión", [
                'usuario_id' => $_SESSION['user-id']
            ]);
        }

        header('Location: ?route=dashboard');
        exit();
    }
}