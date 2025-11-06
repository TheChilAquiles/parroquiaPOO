<?php
/**
 * Script temporal para vincular usuarios feligreses con registros de feligreses
 * Uso: php vincular-usuarios-feligreses.php
 */

require_once __DIR__ . '/Modelo/Conexion.php';

echo "=================================================\n";
echo "VINCULACIÓN DE USUARIOS CON FELIGRESES\n";
echo "=================================================\n\n";

try {
    $conexion = Conexion::conectar();

    // Obtener usuarios feligreses sin vincular
    $sqlUsuarios = "SELECT u.id, u.email 
                    FROM usuarios u 
                    JOIN usuario_roles ur ON u.usuario_rol_id = ur.id 
                    WHERE ur.rol = 'Feligres' 
                    AND u.estado_registro IS NULL
                    AND NOT EXISTS (
                        SELECT 1 FROM feligreses f WHERE f.usuario_id = u.id
                    )";
    
    $stmtUsuarios = $conexion->prepare($sqlUsuarios);
    $stmtUsuarios->execute();
    $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

    // Obtener feligreses sin usuario
    $sqlFeligreses = "SELECT id, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, numero_documento
                      FROM feligreses 
                      WHERE usuario_id IS NULL 
                      AND estado_registro IS NULL";
    
    $stmtFeligreses = $conexion->prepare($sqlFeligreses);
    $stmtFeligreses->execute();
    $feligreses = $stmtFeligreses->fetchAll(PDO::FETCH_ASSOC);

    echo "Usuarios sin vincular: " . count($usuarios) . "\n";
    echo "Feligreses sin usuario: " . count($feligreses) . "\n\n";

    if (count($usuarios) === 0) {
        echo "✓ Todos los usuarios ya están vinculados.\n";
        exit(0);
    }

    if (count($feligreses) === 0) {
        echo "✗ No hay feligreses disponibles para vincular.\n";
        exit(1);
    }

    echo "Opciones:\n";
    echo "1. Vincular automáticamente (1 a 1 en orden)\n";
    echo "2. Mostrar lista para vinculación manual\n";
    echo "3. Salir\n\n";

    echo "Selecciona una opción: ";
    $opcion = trim(fgets(STDIN));

    switch ($opcion) {
        case '1':
            // Vinculación automática
            $vinculados = 0;
            $limite = min(count($usuarios), count($feligreses));

            for ($i = 0; $i < $limite; $i++) {
                $usuario = $usuarios[$i];
                $feligres = $feligreses[$i];

                $sqlUpdate = "UPDATE feligreses SET usuario_id = ? WHERE id = ?";
                $stmtUpdate = $conexion->prepare($sqlUpdate);
                $stmtUpdate->execute([$usuario['id'], $feligres['id']]);

                echo "✓ Vinculado: {$usuario['email']} → {$feligres['primer_nombre']} {$feligres['primer_apellido']}\n";
                $vinculados++;
            }

            echo "\n=================================================\n";
            echo "Total vinculados: $vinculados\n";
            echo "=================================================\n";
            break;

        case '2':
            // Mostrar lista
            echo "\n--- USUARIOS DISPONIBLES ---\n";
            foreach ($usuarios as $index => $usuario) {
                echo "[U{$index}] ID: {$usuario['id']} - {$usuario['email']}\n";
            }

            echo "\n--- FELIGRESES DISPONIBLES ---\n";
            foreach ($feligreses as $index => $feligres) {
                $nombre = trim("{$feligres['primer_nombre']} {$feligres['segundo_nombre']} {$feligres['primer_apellido']} {$feligres['segundo_apellido']}");
                echo "[F{$index}] ID: {$feligres['id']} - {$nombre} (Doc: {$feligres['numero_documento']})\n";
            }

            echo "\nIngresa las vinculaciones en formato: U0,F0 U1,F1 (o 'salir' para terminar)\n";
            echo "Ejemplo: U0,F2 U1,F0\n";
            echo "> ";

            $input = trim(fgets(STDIN));

            if ($input === 'salir') {
                echo "Proceso cancelado.\n";
                exit(0);
            }

            $pares = explode(' ', $input);
            foreach ($pares as $par) {
                if (strpos($par, ',') !== false) {
                    list($uIdx, $fIdx) = explode(',', $par);
                    $uIdx = (int)str_replace('U', '', $uIdx);
                    $fIdx = (int)str_replace('F', '', $fIdx);

                    if (isset($usuarios[$uIdx]) && isset($feligreses[$fIdx])) {
                        $usuario = $usuarios[$uIdx];
                        $feligres = $feligreses[$fIdx];

                        $sqlUpdate = "UPDATE feligreses SET usuario_id = ? WHERE id = ?";
                        $stmtUpdate = $conexion->prepare($sqlUpdate);
                        $stmtUpdate->execute([$usuario['id'], $feligres['id']]);

                        echo "✓ Vinculado: {$usuario['email']} → {$feligres['primer_nombre']} {$feligres['primer_apellido']}\n";
                    }
                }
            }
            break;

        case '3':
        default:
            echo "Proceso cancelado.\n";
            exit(0);
    }

} catch (PDOException $e) {
    echo "✗ Error de base de datos: " . $e->getMessage() . "\n";
    exit(1);
}
