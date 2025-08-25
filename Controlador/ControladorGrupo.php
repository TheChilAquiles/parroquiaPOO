<?php
// Asegúrate de que las rutas a tus modelos sean correctas
require_once(__DIR__ . '/../Modelo/ModeloGrupo.php');
require_once(__DIR__ . '/../Modelo/ModeloUsuario.php'); 

class GrupoController
{
    private $modeloGrupo;
    private $modeloUsuario;

    public function __construct()
    {
        $this->modeloGrupo = new ModeloGrupo();
        $this->modeloUsuario = new ModeloUsuario();
    }
    
    // Método principal que gestiona todas las acciones de los grupos
    public function ctrlGestionarGrupos()
    {
        // Obtener la acción de la URL, si existe
        $action = isset($_GET['action']) ? $_GET['action'] : null;

        switch ($action) {
            case 'ver':
                // Si la acción es 'ver' y se proporciona un ID, mostramos el detalle del grupo
                if (isset($_GET['id'])) {
                    $grupo = $this->modeloGrupo->mdlObtenerGrupoPorId($_GET['id']);
                    $miembros = $this->modeloGrupo->mdlListarMiembrosGrupo($_GET['id']);
                    
                    if (!$grupo) {
                        // Manejar error si el grupo no existe
                        echo "Grupo no encontrado.";
                        // O redirigir a la lista de grupos
                        // header('Location: index.php?menu-item=Grupos');
                        // exit();
                    }
                    
                    include_once(__DIR__ . '/../Vista/grupoDetalle.php');
                }
                break;

            case 'crear':
                // Si la acción es 'crear' y el método es POST, creamos el grupo
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_grupo'])) {
                    $resultado = $this->modeloGrupo->mdlCrearGrupo($_POST['nombre_grupo']);
                    if ($resultado) {
                        echo "<script>alert('Grupo creado con éxito.');</script>";
                    } else {
                        echo "<script>alert('Error al crear el grupo.');</script>";
                    }
                    // Después de crear, redirigimos a la lista de grupos para evitar reenvío del formulario
                    header('Location: index.php?menu-item=Grupos');
                    exit();
                }
                break;
            
            case 'agregar_miembro':
                // Lógica para agregar un miembro
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['grupo_id'], $_POST['email_miembro'])) {
                    $usuario = $this->modeloUsuario->consultarUsuario($_POST['email_miembro']);
                    if ($usuario) {
                        $this->modeloGrupo->mdlAgregarMiembro($_POST['grupo_id'], $usuario['id'], $_POST['rol_id']);
                    }
                    header('Location: index.php?menu-item=Grupos&action=ver&id=' . $_POST['grupo_id']);
                    exit();
                }
                break;
                
            case 'eliminar_miembro':
                // Lógica para eliminar un miembro
                if (isset($_GET['grupo_id']) && isset($_GET['usuario_id'])) {
                    $this->modeloGrupo->mdlEliminarMiembro($_GET['grupo_id'], $_GET['usuario_id']);
                    header('Location: index.php?menu-item=Grupos&action=ver&id=' . $_GET['grupo_id']);
                    exit();
                }
                break;

            default:
                // Si no hay acción o no es reconocida, mostramos la lista de grupos por defecto
                $grupos = $this->modeloGrupo->mdlListarGrupos();
                include_once(__DIR__ . '/../Vista/grupos.php');
                break;
        }
    }
}
?>