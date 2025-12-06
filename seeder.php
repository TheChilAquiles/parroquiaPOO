<?php

require_once 'vendor/autoload.php';

// Cargar variables de entorno
try {
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    } elseif (file_exists(__DIR__ . '/.env.production')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.production');
        $dotenv->load();
    }
} catch (Exception $e) {
    echo "Aviso: No se pudo cargar .env (" . $e->getMessage() . ")\n";
}

require_once 'config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    echo "Conectando a MySQL...\n";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Crear base de datos si no existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci");
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // Desactivar checks de llaves foráneas
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Lista de tablas para limpiar
    $tables = [
        'pago_ordenes', 'reportes', 'pagos', 'certificados', 'participantes', 'participantes_rol',
        'sacramentos', 'sacramento_tipo', 'libros', 'libro_tipo', 'parientes', 'parentescos',
        'usuario_grupos', 'feligreses', 'documento_tipos', 'noticias', 'usuarios', 'usuario_roles',
        'grupos', 'grupo_roles', 'tipos_pago', 'configuraciones'
    ];
    
    echo "Limpiando tablas...\n";
    foreach ($tables as $table) {
        try {
            $pdo->query("SELECT 1 FROM `$table` LIMIT 1");
            $pdo->exec("TRUNCATE TABLE `$table`");
        } catch (PDOException $e) {
            // Ignorar
        }
    }
    
    if (file_exists('Data/migrations/create_pago_ordenes_table.sql')) {
        $migrationSql = file_get_contents('Data/migrations/create_pago_ordenes_table.sql');
        $pdo->exec($migrationSql);
    }
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "Iniciando Seeding de datos...\n";

    // 1. ROLES DE USUARIO
    $pdo->exec("INSERT INTO usuario_roles (id, rol) VALUES (1, 'Feligres'), (2, 'Administrador'), (3, 'Secretario')");
        
    // 2. TIPOS DE DOCUMENTO
    $pdo->exec("INSERT INTO documento_tipos (id, tipo) VALUES (1, 'Cédula de Ciudadanía'), (2, 'Tarjeta de Identidad'), (3, 'Cédula de Extranjería'), (4, 'Registro Civil'), (5, 'Permiso Especial'), (6, 'NIT')");
        
    // 3. TIPOS DE SACRAMENTO
    $pdo->exec("INSERT INTO sacramento_tipo (id, tipo) VALUES (1, 'Bautizo'), (2, 'Confirmación'), (3, 'Defunción'), (4, 'Matrimonio')");
        
    // 4. TIPOS DE LIBRO
    $pdo->exec("INSERT INTO libro_tipo (id, tipo) VALUES (1, 'Bautizos'), (2, 'Confirmaciones'), (3, 'Defunciones'), (4, 'Matrimonios')");
        
    // 5. PARENTESCOS
    $pdo->exec("INSERT INTO parentescos (id, parentesco) VALUES (1, 'Padre'), (2, 'Madre'), (3, 'Hermano/a'), (4, 'Abuelo/a'), (5, 'Tío/a'), (6, 'Esposo/a')");

    // 6. ROLES PARTICIPANTES
    $pdo->exec("INSERT INTO participantes_rol (id, rol) VALUES 
        (1, 'Bautizado'), (2, 'Padrino'), (3, 'Madrina'), 
        (4, 'Padre'), (5, 'Madre'), 
        (6, 'Confirmando'), 
        (7, 'Esposo'), (8, 'Esposa'), (9, 'Testigo'),
        (10, 'Difunto')");

    // 7. TIPOS DE PAGO
    $pdo->exec("INSERT INTO tipos_pago (id, descripcion) VALUES (1, 'Efectivo'), (2, 'Tarjeta Crédito'), (3, 'Transferencia'), (4, 'Pasarela Web')");

    // 8. CONFIGURACIONES
    $stmtConfig = $pdo->prepare("INSERT INTO configuraciones (clave, valor, tipo, categoria, descripcion) VALUES (?, ?, ?, ?, ?)");
    $configs = [
        ['parroquia_nombre', 'Parroquia San Francisco de Asís', 'texto', 'parroquia', 'Nombre de la parroquia'],
        ['parroquia_telefono', '601 402 3526', 'texto', 'parroquia', 'Teléfono principal'],
        ['cert_precio_bautismo', '25000', 'numero', 'certificados', 'Precio Bautismo'],
        ['cert_precio_matrimonio', '35000', 'numero', 'certificados', 'Precio Matrimonio'],
        ['pago_moneda', 'COP', 'texto', 'pagos', 'Moneda'],
        ['pago_modo', 'sandbox', 'texto', 'pagos', 'Modo pasarela'],
        ['sistema_registro_abierto', '1', 'booleano', 'sistema', 'Registro abierto']
    ];
    foreach($configs as $cfg) {
        $stmtConfig->execute($cfg);
    }

    // 9. USUARIOS Y FELIGRESES PRINCIPALES
    $pass = password_hash('admin123', PASSWORD_BCRYPT);
    
    // Staff
    $pdo->prepare("INSERT INTO usuarios (id, usuario_rol_id, email, contraseña, datos_completos, email_confirmed) VALUES (?, ?, ?, ?, 1, 1)")->execute([1, 2, 'admin@parroquia.com', $pass]);
    $pdo->prepare("INSERT INTO usuarios (id, usuario_rol_id, email, contraseña, datos_completos, email_confirmed) VALUES (?, ?, ?, ?, 1, 1)")->execute([2, 3, 'secretaria@parroquia.com', $pass]);

    // Feligreses con usuario (Actores principales)
    $feligresesData = [
        [10, 'juan@mail.com', 'Juan', 'Perez', '1001'],       // Bautizo
        [11, 'maria@mail.com', 'Maria', 'Gomez', '1002'],      // Confirmación
        [12, 'pedro@mail.com', 'Pedro', 'Rodriguez', '1003'],  // Matrimonio (Esposo)
        [13, 'ana@mail.com', 'Ana', 'Martinez', '1004'],       // Matrimonio (Esposa)
        [14, 'roberto@mail.com', 'Roberto', 'Diaz', '1005']    // Defunción
    ];
    
    foreach ($feligresesData as $u) {
        $pdo->prepare("INSERT INTO usuarios (id, usuario_rol_id, email, contraseña, datos_completos, email_confirmed) VALUES (?, ?, ?, ?, 1, 1)")
            ->execute([$u[0], 1, $u[1], $pass]);
        
        // CORREGIDO: estado_registro = NULL para que esté activo
        $pdo->prepare("INSERT INTO feligreses (usuario_id, tipo_documento_id, numero_documento, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono, direccion, estado_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)")
            ->execute([$u[0], 1, $u[4], $u[2], '', $u[3], '', '3001234567', 'Calle Falsa 123']);
    }

    // FELIGRESES "EXTRA"
    $extras = [
        ['Carlos', 'Perez', '2001', 4, '123' ], // Padre Juan
        ['Luisa', 'Mendez', '2002', 5, '456' ], // Madre Juan
        ['Andres', 'Lopez', '2003', 2, '789' ], // Padrino Juan / Maria
        ['Elena', 'Gacia', '2004', 3, '101' ],  // Madrina Juan
        ['Marcos', 'Ruiz', '2005', 9, '112' ],  // Testigo Matrimonio
        ['Sofia', 'Vargas', '2006', 9, '113' ]  // Testigo Matrimonio
    ];

    $extraIds = [];
    foreach ($extras as $e) {
        // CORREGIDO: estado_registro = NULL
        $pdo->prepare("INSERT INTO feligreses (usuario_id, tipo_documento_id, numero_documento, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono, direccion, estado_registro) VALUES (NULL, 1, ?, ?, '', ?, '', '0000000', 'N/A', NULL)")
            ->execute([$e[2], $e[0], $e[1]]);
        $extraIds[] = $pdo->lastInsertId(); 
    }
    // Asignación manual:
    $idPadreJuan = $extraIds[0];
    $idMadreJuan = $extraIds[1];
    $idPadrino = $extraIds[2];
    $idMadrina = $extraIds[3];
    $idTestigo1 = $extraIds[4];
    $idTestigo2 = $extraIds[5];


    // 10. LIBROS (estado_registro = NULL)
    $pdo->exec("INSERT INTO libros (id, libro_tipo_id, numero, estado_registro) VALUES (1, 1, 1, NULL)"); 
    $pdo->exec("INSERT INTO libros (id, libro_tipo_id, numero, estado_registro) VALUES (2, 2, 1, NULL)"); 
    $pdo->exec("INSERT INTO libros (id, libro_tipo_id, numero, estado_registro) VALUES (3, 3, 1, NULL)"); 
    $pdo->exec("INSERT INTO libros (id, libro_tipo_id, numero, estado_registro) VALUES (4, 4, 1, NULL)"); 

    // 11. SACRAMENTOS Y PARTICIPANTES
    echo "Creando ejemplos de sacramentos y participantes...\n";

    // --- A. BAUTIZO (Juan) ---
    $idJuan = $pdo->query("SELECT id FROM feligreses WHERE usuario_id = 10")->fetchColumn();
    // CORREGIDO: estado_registro = NULL
    $pdo->prepare("INSERT INTO sacramentos (libro_id, tipo_sacramento_id, acta, folio, fecha_generacion, estado_registro) VALUES (?, ?, ?, ?, ?, NULL)")
        ->execute([1, 1, 101, 10, '2000-01-15']); 
    $sacBautizo = $pdo->lastInsertId();
    
    $partsBautizo = [
        [$idJuan, 1],       // Bautizado
        [$idPadreJuan, 4],  // Padre
        [$idMadreJuan, 5],  // Madre
        [$idPadrino, 2],    // Padrino
        [$idMadrina, 3]     // Madrina
    ];
    foreach($partsBautizo as $p) {
        $pdo->prepare("INSERT INTO participantes (feligres_id, sacramento_id, rol_participante_id) VALUES (?, ?, ?)")
            ->execute([$p[0], $sacBautizo, $p[1]]); 
    }
    echo "- Bautizo creado para Juan Perez.\n";


    // --- B. CONFIRMACIÓN (Maria) ---
    $idMaria = $pdo->query("SELECT id FROM feligreses WHERE usuario_id = 11")->fetchColumn();
    // CORREGIDO: estado_registro = NULL
    $pdo->prepare("INSERT INTO sacramentos (libro_id, tipo_sacramento_id, acta, folio, fecha_generacion, estado_registro) VALUES (?, ?, ?, ?, ?, NULL)")
        ->execute([2, 2, 201, 20, '2015-06-20']); 
    $sacConfirma = $pdo->lastInsertId();
    
    $pdo->prepare("INSERT INTO participantes (feligres_id, sacramento_id, rol_participante_id) VALUES (?, ?, ?)")
        ->execute([$idMaria, $sacConfirma, 6]); 
    $pdo->prepare("INSERT INTO participantes (feligres_id, sacramento_id, rol_participante_id) VALUES (?, ?, ?)")
        ->execute([$idPadrino, $sacConfirma, 2]); 
    echo "- Confirmación creada para Maria Gomez.\n";


    // --- C. DEFUNCIÓN (Roberto) ---
    $idRoberto = $pdo->query("SELECT id FROM feligreses WHERE usuario_id = 14")->fetchColumn();
    // CORREGIDO: estado_registro = NULL
    $pdo->prepare("INSERT INTO sacramentos (libro_id, tipo_sacramento_id, acta, folio, fecha_generacion, estado_registro) VALUES (?, ?, ?, ?, ?, NULL)")
        ->execute([3, 3, 301, 30, '2023-11-01']); 
    $sacDefuncion = $pdo->lastInsertId();
    $pdo->prepare("INSERT INTO participantes (feligres_id, sacramento_id, rol_participante_id) VALUES (?, ?, ?)")
        ->execute([$idRoberto, $sacDefuncion, 10]); 
    echo "- Defunción creada para Roberto Diaz.\n";


    // --- D. MATRIMONIO (Pedro y Ana) ---
    $idPedro = $pdo->query("SELECT id FROM feligreses WHERE usuario_id = 12")->fetchColumn();
    $idAna = $pdo->query("SELECT id FROM feligreses WHERE usuario_id = 13")->fetchColumn();
    // CORREGIDO: estado_registro = NULL
    $pdo->prepare("INSERT INTO sacramentos (libro_id, tipo_sacramento_id, acta, folio, fecha_generacion, estado_registro) VALUES (?, ?, ?, ?, ?, NULL)")
        ->execute([4, 4, 401, 40, '2024-02-14']); 
    $sacMatrimonio = $pdo->lastInsertId();
    
    $partsMatrimonio = [
        [$idPedro, 7],      // Esposo
        [$idAna, 8],        // Esposa
        [$idTestigo1, 9],   // Testigo 1
        [$idTestigo2, 9]    // Testigo 2
    ];
    foreach($partsMatrimonio as $p) {
        $pdo->prepare("INSERT INTO participantes (feligres_id, sacramento_id, rol_participante_id) VALUES (?, ?, ?)")
            ->execute([$p[0], $sacMatrimonio, $p[1]]); 
    }
    echo "- Matrimonio creado para Pedro y Ana.\n";


    // 12. GRUPOS Y NOTICIAS
    $pdo->exec("INSERT INTO grupo_roles (id, rol) VALUES (1, 'Miembro'), (2, 'Coordinador'), (3, 'Tesorero')");
    $pdo->prepare("INSERT INTO grupos (nombre, estado_registro) VALUES (?, NULL)")->execute(['Legión de María']);
    $pdo->prepare("INSERT INTO grupos (nombre, estado_registro) VALUES (?, NULL)")->execute(['Grupo Juvenil']);
    $groupJoven = $pdo->lastInsertId();
    $pdo->prepare("INSERT INTO usuario_grupos (grupo_parroquial_id, usuario_id, grupo_rol_id, estado_registro) VALUES (?, ?, ?, NULL)")
        ->execute([$groupJoven, 11, 1]); 

    $pdo->prepare("INSERT INTO noticias (id_usuario, titulo, descripcion, imagen, fecha_publicacion, estado_registro) VALUES (?, ?, ?, ?, NOW(), NULL)")
        ->execute([1, 'Inscripciones Abiertas', 'Ya pueden inscribirse para confirmaciones.', 'confirmacion.jpg']);


    // 13. CERTIFICADOS Y PAGOS
    echo "Sembrando certificados y pagos...\n";
    if ($idJuan && $sacBautizo) {
        // estado = generada (o similar), estado_registro = NULL
        $pdo->prepare("INSERT INTO certificados (solicitante_id, feligres_certificado_id, sacramento_id, tipo_certificado, fecha_solicitud, estado, fecha_emision) VALUES (?, ?, ?, ?, NOW(), ?, NOW())")
            ->execute([$idJuan, $idJuan, $sacBautizo, 'Bautismo', 'generado']);
        $certId = $pdo->lastInsertId();
        
        if ($certId) {
            $pdo->prepare("INSERT INTO pagos (certificado_id, valor, estado, tipo_pago_id, fecha_pago) VALUES (?, ?, ?, ?, NOW())")
                ->execute([$certId, 25000.00, 'pagado', 1]);
            $orderNum = 'ORD-' . time();
            $pdo->prepare("INSERT INTO pago_ordenes (certificado_id, order_number, amount, estado, fecha_creacion) VALUES (?, ?, ?, ?, NOW())")
                ->execute([$certId, $orderNum, 25000.00, 'pendiente']);
            echo "- Certificado #$certId y pago generados para Juan.\n";
        }
    }

    echo "\n------------------------------------------------\n";
    echo "¡Seed completado! TODOS los registros están ACTIVOS.\n";
    echo "------------------------------------------------\n";
    echo "- Bautizo: Juan\n";
    echo "- Confirmación: Maria\n";
    echo "- Defunción: Roberto\n";
    echo "- Matrimonio: Pedro y Ana\n";
    
} catch (PDOException $e) {
    die("Error DB: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
