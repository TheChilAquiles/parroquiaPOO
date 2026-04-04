<?php

require_once 'Conexion.php';

class ModeloAdminUsuario
{
    private $conexion;

    // Obtener todos los roles activos para el formulario (Select)
    public function mdlObtenerRoles()
    {
        try {
            $this->conexion = Conexion::conectar();
            $sql = "SELECT id, rol FROM usuario_roles WHERE estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener roles:", ['error' => $e->getMessage()]);
            return [];
        } finally {
            $this->conexion = null;
        }
    }

    // Obtener usuarios cruzando con la tabla de roles
    public function mdlObtenerUsuarios($filtro = '')
    {
        try {
            $this->conexion = Conexion::conectar();
            
            // Agregamos el LEFT JOIN con feligreses y armamos el nombre completo
            $sql = "SELECT u.id, u.email, u.usuario_rol_id, r.rol as nombre_rol, u.datos_completos,
                           CONCAT_WS(' ', f.primer_nombre, f.segundo_nombre, f.primer_apellido, f.segundo_apellido) AS nombre_completo,
                           f.numero_documento
                    FROM usuarios u
                    LEFT JOIN usuario_roles r ON u.usuario_rol_id = r.id
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    WHERE u.estado_registro IS NULL";
            
            // Ampliamos el filtro para que también busque por nombre, apellido o documento
            if (!empty($filtro)) {
                $sql .= " AND (u.email LIKE :filtro 
                            OR r.rol LIKE :filtro 
                            OR f.primer_nombre LIKE :filtro 
                            OR f.primer_apellido LIKE :filtro
                            OR f.numero_documento LIKE :filtro)";
            }
            
            $sql .= " ORDER BY u.id DESC";
            
            $stmt = $this->conexion->prepare($sql);
            
            if (!empty($filtro)) {
                $stmt->bindValue(':filtro', '%' . $filtro . '%', PDO::PARAM_STR);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            Logger::error("Error al obtener usuarios:", ['error' => $e->getMessage()]);
            return [];
        } finally {
            $this->conexion = null;
        }
    }

    public function mdlCrearUsuario(array $datos)
    {
        try {
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            // Verificar si el email ya existe (que no esté eliminado)
            $check = $this->conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND estado_registro IS NULL");
            $check->execute([$datos['email']]);
            if ($check->rowCount() > 0) {
                return ['exito' => false, 'mensaje' => "El email ya está registrado."];
            }

            $sql = "INSERT INTO usuarios (email, contraseña, usuario_rol_id, email_confirmed, datos_completos) 
                    VALUES (?, ?, ?, 1, 0)";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $datos['email'],
                $datos['contraseña'],
                $datos['usuario_rol_id']
            ]);

            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Usuario creado correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se pudo crear el usuario."];
            }
            
        } catch (PDOException $e) {
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            Logger::error("Error al crear usuario:", ['error' => $e->getMessage()]);
            return ['exito' => false, 'mensaje' => "Error interno al guardar el usuario."];
        } finally {
            $this->conexion = null;
        }
    }

    public function mdlActualizarUsuario(int $id, array $datos)
    {
        try {
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            // Verificar que el email no le pertenezca a otro usuario
            $check = $this->conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ? AND estado_registro IS NULL");
            $check->execute([$datos['email'], $id]);
            if ($check->rowCount() > 0) {
                return ['exito' => false, 'mensaje' => "El email ya está en uso por otro usuario."];
            }

            // Query dinámica: solo actualiza contraseña si se envió una nueva
            $sql = "UPDATE usuarios SET email = ?, usuario_rol_id = ?";
            $params = [$datos['email'], $datos['usuario_rol_id']];

            if (isset($datos['contraseña'])) {
                $sql .= ", contraseña = ?";
                $params[] = $datos['contraseña'];
            }

            $sql .= " WHERE id = ?";
            $params[] = $id;

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);

            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Usuario actualizado correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se realizaron cambios."];
            }
            
        } catch (PDOException $e) {
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            Logger::error("Error al actualizar usuario:", ['error' => $e->getMessage()]);
            return ['exito' => false, 'mensaje' => "Error interno al actualizar."];
        } finally {
            $this->conexion = null;
        }
    }

    public function mdlBorrarUsuario($id)
    {
        try {
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            $sql = "UPDATE usuarios SET estado_registro = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([date('Y-m-d H:i:s'), $id]);

            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Usuario eliminado correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se encontró el usuario."];
            }
            
        } catch (PDOException $e) {
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            Logger::error("Error al borrar usuario:", ['error' => $e->getMessage()]);
            return ['exito' => false, 'mensaje' => "Error interno al borrar."];
        } finally {
            $this->conexion = null;
        }
    }
}