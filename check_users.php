<?php
$db = new PDO('mysql:host=localhost;dbname=parroquia', 'root', '');

// Primero ver la estructura de la tabla
echo "Estructura de la tabla usuarios:\n";
echo "================================\n";
$stmt = $db->query('DESCRIBE usuarios');
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}

echo "\n\nUsuarios en la base de datos:\n";
echo "============================\n\n";

// Ahora listar usuarios
$stmt = $db->query('SELECT * FROM usuarios LIMIT 5');
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario) {
    foreach ($usuario as $key => $value) {
        echo "$key: $value\n";
    }
    echo "----------------------------\n";
}
