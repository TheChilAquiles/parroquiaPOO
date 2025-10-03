<?php

/**
 * @file ModeloNoticia.php
 * @version 1.5
 * @author Samuel Bedoya
 * @brief Modelo de datos para la entidad Noticias
 * 
 * Implementa el patrón Data Access Object (DAO) para gestionar todas las
 * operaciones CRUD de la tabla 'noticias' con la base de datos.
 * 
 * @architecture
 * - Utiliza PDO para consultas preparadas (prevención de SQL Injection)
 * - Implementa transacciones para operaciones críticas
 * - Manejo centralizado de errores con logging
 * - Borrado lógico en lugar de físico (soft delete)
 * 
 * @security
 * - Prepared Statements en todas las consultas
 * - Manejo de excepciones PDO
 * - Validación de datos en controlador (este modelo confía en datos limpios)
 * 
 * @package Modelo
 * @dependency Conexion.php - Clase que maneja la conexión PDO
 */

require_once 'Conexion.php';

class ModeloNoticia
{
    /**
     * Conexión PDO a la base de datos.
     * Se inicializa en cada método para evitar conexiones persistentes.
     * 
     * @var PDO|null
     */
    private $conexion;

    // ========================================================================
    // OPERACIONES CRUD
    // ========================================================================

    /**
     * Crea un nuevo registro de noticia en la base de datos.
     * 
     * Implementa transacción para garantizar atomicidad de la operación.
     * Si algo falla, se revierte automáticamente.
     * 
     * @param array $datos Datos de la noticia a crear
     * @param int    $datos['id_usuario']  ID del usuario que crea la noticia
     * @param string $datos['titulo']      Título de la noticia
     * @param string $datos['descripcion'] Contenido descriptivo
     * @param string $datos['imagen']      Ruta de la imagen
     * 
     * @return array Resultado de la operación
     *               ['exito' => bool, 'mensaje' => string]
     * 
     * @throws PDOException En caso de error de base de datos
     */
    public function mdlCrearNoticia($datos)
    {
        $stmt = null; // Inicializar para acceso en finally
        
        try {
            // Establece conexión y comienza transacción
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            // Prepara consulta con placeholders (?)
            $sql = "INSERT INTO `noticias` (`id_usuario`, `titulo`, `descripcion`, `imagen`) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);

            // Ejecuta con binding automático de parámetros
            $stmt->execute([
                $datos['id_usuario'],
                $datos['titulo'],
                $datos['descripcion'],
                $datos['imagen']
            ]);

            // Verifica que se insertó al menos una fila
            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Noticia creada correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se pudo crear la noticia."];
            }
            
        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            
            // Registra error en logs del servidor (no expone al usuario)
            error_log("Error al crear noticia: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al guardar la noticia."];
            
        } finally {
            // Libera recursos independientemente del resultado
            $stmt = null;
            $this->conexion = null;
        }
    }

    /**
     * Obtiene todas las noticias no eliminadas (borrado lógico).
     * 
     * Soporta búsqueda por término en título o descripción.
     * Los resultados se ordenan por fecha de publicación descendente.
     * 
     * @param string $filtro Término de búsqueda opcional (default: '')
     * 
     * @return array Lista de noticias como arrays asociativos
     *               Retorna array vacío si no hay resultados o hay error
     * 
     * @example
     * $modelo->mdlObtenerNoticias('evento'); // Busca noticias con "evento"
     * $modelo->mdlObtenerNoticias();         // Obtiene todas las noticias
     */
    public function mdlObtenerNoticias($filtro = '')
    {
        $stmt = null;
        $noticias = [];
        
        try {
            $this->conexion = Conexion::conectar();
            
            // Consulta base: solo noticias no eliminadas (estado_registro IS NULL)
            $sql = "SELECT * FROM `noticias` WHERE `estado_registro` IS NULL";
            
            // Añade cláusula de búsqueda si hay filtro
            if (!empty($filtro)) {
                $sql .= " AND (`titulo` LIKE :filtro OR `descripcion` LIKE :filtro)";
            }
            
            // Ordena por fecha más reciente primero
            $sql .= " ORDER BY `fecha_publicacion` DESC";
            
            $stmt = $this->conexion->prepare($sql);
            
            // Bind de parámetro con wildcards para LIKE
            if (!empty($filtro)) {
                $stmt->bindValue(':filtro', '%' . $filtro . '%', PDO::PARAM_STR);
            }
            
            $stmt->execute();
            $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Log del error sin exponer detalles al usuario
            error_log("Error al obtener noticias: " . $e->getMessage());
            
        } finally {
            $stmt = null;
            $this->conexion = null;
        }

        return $noticias;
    }

    /**
     * Actualiza una noticia existente.
     * 
     * Solo actualiza título, descripción e imagen. No modifica ID ni usuario.
     * Usa transacción para garantizar consistencia.
     * 
     * @param int $id El ID de la noticia a actualizar
     * @param array $datos Datos a actualizar
     * @param string $datos['titulo']      Nuevo título
     * @param string $datos['descripcion'] Nueva descripción
     * @param string $datos['imagen']      Nueva ruta de imagen
     * 
     * @return array Resultado de la operación
     *               ['exito' => bool, 'mensaje' => string]
     * 
     * @note Si no se modifican filas (mismos valores), retorna exito=false
     */
    public function mdlActualizarNoticia($id, $datos)
    {
        $stmt = null;
        
        try {
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            // UPDATE con binding posicional de parámetros
            $sql = "UPDATE `noticias` 
                    SET `titulo` = ?, `descripcion` = ?, `imagen` = ? 
                    WHERE `id` = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $datos['titulo'],
                $datos['descripcion'],
                $datos['imagen'],
                $id
            ]);

            // rowCount() indica cuántas filas fueron afectadas
            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Noticia actualizada correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se encontró la noticia para actualizar o no se realizaron cambios."];
            }
            
        } catch (PDOException $e) {
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            error_log("Error al actualizar noticia: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al actualizar la noticia."];
            
        } finally {
            $stmt = null;
            $this->conexion = null;
        }
    }

    /**
     * Realiza un borrado lógico de una noticia.
     * 
     * NO elimina el registro físicamente, solo marca la fecha de eliminación
     * en el campo 'estado_registro'. Esto permite auditoría y recuperación.
     * 
     * @param int $id El ID de la noticia a "eliminar"
     * 
     * @return array Resultado de la operación
     *               ['exito' => bool, 'mensaje' => string]
     * 
     * @pattern Soft Delete
     * @benefits
     * - Permite recuperación de datos eliminados accidentalmente
     * - Mantiene integridad referencial
     * - Facilita auditorías y compliance
     */
    public function mdlBorrarNoticia($id)
    {
        $stmt = null;
        
        try {
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            // Marca con timestamp la fecha de "eliminación"
            $sql = "UPDATE `noticias` SET `estado_registro` = ? WHERE `id` = ?";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([date('Y-m-d H:i:s'), $id]);

            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Noticia eliminada correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se encontró la noticia para eliminar."];
            }
            
        } catch (PDOException $e) {
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            error_log("Error al borrar noticia: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al borrar la noticia."];
            
        } finally {
            $stmt = null;
            $this->conexion = null;
        }
    }
}