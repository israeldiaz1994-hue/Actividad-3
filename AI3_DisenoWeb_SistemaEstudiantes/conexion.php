<?php
declare(strict_types=1);

/*
 * Configuración local XAMPP.
 * Si necesitas otras credenciales, crea config.local.php (está en .gitignore)
 * y define allí DB_HOST, DB_NAME, DB_USER y DB_PASS.
 */
$localConfig = __DIR__ . '/config.local.php';
if (file_exists($localConfig)) {
    require_once $localConfig;
}

$host = defined('DB_HOST') ? DB_HOST : 'localhost';
$dbname = defined('DB_NAME') ? DB_NAME : 'db_sistema_web';
$user = defined('DB_USER') ? DB_USER : 'root';
$password = defined('DB_PASS') ? DB_PASS : '';

try {
    $conexion = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Error de conexión PDO: ' . $e->getMessage());
    http_response_code(500);
    exit('No se pudo conectar con la base de datos. Verifique la configuración del servidor.');
}
