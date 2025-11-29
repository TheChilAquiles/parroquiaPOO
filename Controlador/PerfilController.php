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
        try {
            // Verificar autenticación pero permitir perfil incompleto
            $this->requiereAutenticacion(true);

            Logger::info("Búsqueda de feligrés iniciada", [
                'user_id' => $_SESSION['user-id'] ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->mostrar();
                return;
            }

            $tipoDoc = $_POST['tipoDocumento'] ?? null;
            $numeroDoc = $_POST['numeroDocumento'] ?? null;

            if (empty($tipoDoc) || empty($numeroDoc)) {
                Logger::warning("Búsqueda de feligrés: campos vacíos", [
                    'user_id' => $_SESSION['user-id'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error'] = 'Tipo y número de documento son requeridos.';
                $this->mostrar();
                return;
            }

            $feligres = $this->modeloFeligres->mdlConsultarFeligres($tipoDoc, $numeroDoc);

            if ($feligres === false) {
                Logger::warning("Feligrés no encontrado", [
                    'user_id' => $_SESSION['user-id'],
                    'tipo_doc' => $tipoDoc,
                    'numero_doc_prefix' => substr($numeroDoc, 0, 3) . '***'
                ]);
                $_SESSION['error'] = 'No se encontró un feligrés con estos datos. Puedes registrarte llenando el formulario.';
                $this->mostrar();
                return;
            }

            Logger::info("Feligrés encontrado exitosamente", [
                'user_id' => $_SESSION['user-id'],
                'feligres_id' => $feligres['id'] ?? 'unknown',
                'tipo_doc' => $tipoDoc,
                'numero_doc_prefix' => substr($numeroDoc, 0, 3) . '***'
            ]);

            $_SESSION['feligres_temporal'] = $feligres;
            include_once __DIR__ . '/../Vista/datos-personales.php';

        } catch (Exception $e) {
            Logger::error("Error crítico en búsqueda de feligrés", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $_SESSION['user-id'] ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $_SESSION['error'] = 'Error al buscar tus datos. Por favor, intenta de nuevo.';
            $this->mostrar();
        }
    }

    public function actualizar()
    {
        try {
            // Verificar autenticación pero permitir perfil incompleto
            $this->requiereAutenticacion(true);

            Logger::info("Actualización de perfil iniciada", [
                'user_id' => $_SESSION['user-id'],
                'datos_completos_previo' => $_SESSION['user-datos'] ?? false,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

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

                Logger::warning("Actualización de perfil fallida: campos requeridos vacíos", [
                    'user_id' => $_SESSION['user-id'],
                    'campos_faltantes' => array_keys(array_filter($datosFeligres, function($val) {
                        return empty($val);
                    }))
                ]);

                $_SESSION['error'] = 'Completa todos los campos requeridos.';
                $this->mostrar();
                return;
            }

            // Crear o actualizar feligres
            $esNuevo = ($_SESSION['user-datos'] == false);

            if ($esNuevo) {
                Logger::info("Creando nuevo perfil de feligrés", [
                    'user_id' => $_SESSION['user-id'],
                    'tipo_doc' => $datosFeligres['tipo-doc'],
                    'numero_doc_prefix' => substr($datosFeligres['documento'], 0, 3) . '***'
                ]);
                $status = $this->modeloFeligres->mdlCrearFeligres($datosFeligres);
            } else {
                Logger::info("Actualizando perfil de feligrés existente", [
                    'user_id' => $_SESSION['user-id'],
                    'tipo_doc' => $datosFeligres['tipo-doc'],
                    'numero_doc_prefix' => substr($datosFeligres['documento'], 0, 3) . '***'
                ]);
                // Obtener ID del feligrés por usuario_id
                $feligres = $this->modeloFeligres->mdlConsultarFeligres($datosFeligres['tipo-doc'], $datosFeligres['documento']);
                if ($feligres) {
                    $datosFeligres['id'] = $feligres['id'];
                }
                $status = $this->modeloFeligres->mdlUpdateFeligres($datosFeligres);
            }

            if ($status['status'] === 'error') {
                Logger::error("Error al actualizar/crear perfil de feligrés", [
                    'user_id' => $_SESSION['user-id'],
                    'es_nuevo' => $esNuevo,
                    'error' => $status['error']
                ]);
                $_SESSION['error'] = $status['error'];
                $this->mostrar();
                return;
            }

            // Actualizar el campo datos_completos en la tabla usuarios
            $actualizado = $this->modeloUsuario->mdlMarcarDatosCompletos($_SESSION['user-id']);

            if ($actualizado) {
                $_SESSION['user-datos'] = true;
                $_SESSION['success'] = 'Datos actualizados correctamente.';

                Logger::info("Perfil actualizado exitosamente", [
                    'user_id' => $_SESSION['user-id'],
                    'feligres_id' => $status['id'] ?? 'unknown',
                    'es_nuevo' => $esNuevo,
                    'datos_completos_actualizado' => true
                ]);
            } else {
                // Aun así marcamos la sesión como completa
                $_SESSION['user-datos'] = true;
                $_SESSION['success'] = 'Datos actualizados correctamente.';

                Logger::warning("No se pudo actualizar datos_completos en BD pero se marcó en sesión", [
                    'usuario_id' => $_SESSION['user-id'],
                    'feligres_id' => $status['id'] ?? 'unknown'
                ]);
            }

            header('Location: ?route=dashboard');
            exit();

        } catch (Exception $e) {
            Logger::error("Error crítico en actualización de perfil", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $_SESSION['user-id'] ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $_SESSION['error'] = 'Error al actualizar tus datos. Por favor, intenta de nuevo.';
            $this->mostrar();
        }
    }
}