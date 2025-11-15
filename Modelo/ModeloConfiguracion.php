<?php

/**
 * ModeloConfiguracion
 * Modelo para gestionar las configuraciones del sistema
 */
class ModeloConfiguracion
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Obtiene el valor de una configuración por su clave
     *
     * @param string $clave
     * @param mixed $valorPorDefecto Valor a devolver si no existe la configuración
     * @return mixed
     */
    public function obtenerPorClave($clave, $valorPorDefecto = null)
    {
        try {
            $sql = "SELECT valor, tipo FROM configuraciones WHERE clave = ? LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$clave]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                return $valorPorDefecto;
            }

            // Convertir el valor según su tipo
            return $this->convertirValor($resultado['valor'], $resultado['tipo']);

        } catch (PDOException $e) {
            Logger::error("Error al obtener configuración", [
                'clave' => $clave,
                'error' => $e->getMessage()
            ]);
            return $valorPorDefecto;
        }
    }

    /**
     * Obtiene todas las configuraciones de una categoría
     *
     * @param string $categoria
     * @return array
     */
    public function obtenerPorCategoria($categoria)
    {
        try {
            $sql = "SELECT * FROM configuraciones WHERE categoria = ? ORDER BY clave ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$categoria]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Convertir valores según su tipo
            foreach ($resultados as &$config) {
                $config['valor_convertido'] = $this->convertirValor($config['valor'], $config['tipo']);
            }

            return $resultados;

        } catch (PDOException $e) {
            Logger::error("Error al obtener configuraciones por categoría", [
                'categoria' => $categoria,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Obtiene todas las configuraciones agrupadas por categoría
     *
     * @return array
     */
    public function obtenerTodas()
    {
        try {
            $sql = "SELECT * FROM configuraciones ORDER BY categoria ASC, clave ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Agrupar por categoría
            $configuraciones = [];
            foreach ($resultados as $config) {
                $categoria = $config['categoria'];
                if (!isset($configuraciones[$categoria])) {
                    $configuraciones[$categoria] = [];
                }
                $config['valor_convertido'] = $this->convertirValor($config['valor'], $config['tipo']);
                $configuraciones[$categoria][] = $config;
            }

            return $configuraciones;

        } catch (PDOException $e) {
            Logger::error("Error al obtener todas las configuraciones", [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Actualiza el valor de una configuración
     *
     * @param string $clave
     * @param mixed $valor
     * @param int|null $usuarioId ID del usuario que realiza la actualización
     * @return bool
     */
    public function actualizar($clave, $valor, $usuarioId = null)
    {
        try {
            // Verificar que la configuración existe y es editable
            $sql = "SELECT id, editable, tipo FROM configuraciones WHERE clave = ? LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$clave]);
            $config = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$config) {
                Logger::warning("Intento de actualizar configuración inexistente", ['clave' => $clave]);
                return false;
            }

            if (!$config['editable']) {
                Logger::warning("Intento de actualizar configuración no editable", ['clave' => $clave]);
                return false;
            }

            // Validar el valor según el tipo
            if (!$this->validarTipo($valor, $config['tipo'])) {
                Logger::warning("Tipo de valor inválido para configuración", [
                    'clave' => $clave,
                    'tipo_esperado' => $config['tipo'],
                    'valor' => $valor
                ]);
                return false;
            }

            // Convertir valor a string para almacenar
            $valorString = $this->convertirAString($valor, $config['tipo']);

            // Actualizar
            $sql = "UPDATE configuraciones
                    SET valor = ?,
                        usuario_actualizacion = ?,
                        fecha_actualizacion = NOW()
                    WHERE clave = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$valorString, $usuarioId, $clave]);

            Logger::info("Configuración actualizada", [
                'clave' => $clave,
                'usuario_id' => $usuarioId
            ]);

            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            Logger::error("Error al actualizar configuración", [
                'clave' => $clave,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Actualiza múltiples configuraciones en una transacción
     *
     * @param array $configuraciones Array asociativo clave => valor
     * @param int|null $usuarioId
     * @return array ['exito' => bool, 'mensaje' => string, 'actualizadas' => int]
     */
    public function actualizarMultiples($configuraciones, $usuarioId = null)
    {
        try {
            $this->conexion->beginTransaction();

            $actualizadas = 0;
            foreach ($configuraciones as $clave => $valor) {
                if ($this->actualizar($clave, $valor, $usuarioId)) {
                    $actualizadas++;
                }
            }

            $this->conexion->commit();

            return [
                'exito' => true,
                'mensaje' => "Se actualizaron {$actualizadas} configuraciones",
                'actualizadas' => $actualizadas
            ];

        } catch (Exception $e) {
            $this->conexion->rollBack();
            Logger::error("Error al actualizar múltiples configuraciones", [
                'error' => $e->getMessage()
            ]);
            return [
                'exito' => false,
                'mensaje' => 'Error al actualizar configuraciones',
                'actualizadas' => 0
            ];
        }
    }

    /**
     * Crea una nueva configuración
     *
     * @param array $datos
     * @return bool
     */
    public function crear($datos)
    {
        try {
            $sql = "INSERT INTO configuraciones
                    (clave, valor, tipo, categoria, descripcion, editable)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $datos['clave'],
                $this->convertirAString($datos['valor'], $datos['tipo']),
                $datos['tipo'],
                $datos['categoria'] ?? 'general',
                $datos['descripcion'] ?? '',
                $datos['editable'] ?? 1
            ]);

            Logger::info("Nueva configuración creada", ['clave' => $datos['clave']]);
            return true;

        } catch (PDOException $e) {
            Logger::error("Error al crear configuración", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Convierte un valor según su tipo
     *
     * @param string $valor
     * @param string $tipo
     * @return mixed
     */
    private function convertirValor($valor, $tipo)
    {
        switch ($tipo) {
            case 'numero':
                return is_numeric($valor) ? (float)$valor : 0;

            case 'booleano':
                return filter_var($valor, FILTER_VALIDATE_BOOLEAN);

            case 'json':
                $decoded = json_decode($valor, true);
                return ($decoded !== null) ? $decoded : [];

            case 'texto':
            case 'email':
            case 'url':
            default:
                return $valor;
        }
    }

    /**
     * Convierte un valor a string para almacenar
     *
     * @param mixed $valor
     * @param string $tipo
     * @return string
     */
    private function convertirAString($valor, $tipo)
    {
        switch ($tipo) {
            case 'booleano':
                return $valor ? '1' : '0';

            case 'json':
                return is_string($valor) ? $valor : json_encode($valor);

            case 'numero':
            case 'texto':
            case 'email':
            case 'url':
            default:
                return (string)$valor;
        }
    }

    /**
     * Valida que un valor sea del tipo correcto
     *
     * @param mixed $valor
     * @param string $tipo
     * @return bool
     */
    private function validarTipo($valor, $tipo)
    {
        switch ($tipo) {
            case 'numero':
                return is_numeric($valor);

            case 'booleano':
                return is_bool($valor) || in_array($valor, ['0', '1', 0, 1, true, false], true);

            case 'email':
                return filter_var($valor, FILTER_VALIDATE_EMAIL) !== false;

            case 'url':
                return filter_var($valor, FILTER_VALIDATE_URL) !== false || empty($valor);

            case 'json':
                if (is_array($valor)) return true;
                json_decode($valor);
                return json_last_error() === JSON_ERROR_NONE;

            case 'texto':
            default:
                return is_string($valor) || is_numeric($valor);
        }
    }

    /**
     * Obtiene las categorías disponibles
     *
     * @return array
     */
    public function obtenerCategorias()
    {
        try {
            $sql = "SELECT DISTINCT categoria FROM configuraciones ORDER BY categoria ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);

        } catch (PDOException $e) {
            Logger::error("Error al obtener categorías", [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Obtiene el precio de un certificado según su tipo
     *
     * @param string $tipoSacramento
     * @return float
     */
    public function obtenerPrecioCertificado($tipoSacramento)
    {
        $tipo = strtolower($tipoSacramento);
        $clave = "cert_precio_{$tipo}";

        $precio = $this->obtenerPorClave($clave);

        // Si no existe precio específico, usar precio general
        if ($precio === null) {
            $precio = $this->obtenerPorClave('cert_precio_general', 10000);
        }

        return (float)$precio;
    }
}
