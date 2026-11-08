<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e($valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validar_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Solicitud no válida. Regrese e inténtelo nuevamente.');
    }
}

function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function obtener_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $mensaje = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $mensaje;
}

function validar_estudiante(array $datos): array
{
    $errores = [];
    $limites = [
        'nombres' => 100,
        'apellidos' => 100,
        'correo' => 150,
        'telefono' => 20,
        'carrera' => 100,
    ];

    foreach ($limites as $campo => $maximo) {
        if (!isset($datos[$campo]) || trim((string)$datos[$campo]) === '') {
            $errores[] = 'Todos los campos son obligatorios.';
            break;
        }
        if (mb_strlen(trim((string)$datos[$campo])) > $maximo) {
            $errores[] = 'Uno o más campos superan la longitud permitida.';
            break;
        }
    }

    if (!empty($datos['correo']) &&
        !filter_var(trim((string)$datos['correo']), FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingrese un correo electrónico válido.';
    }

    return array_unique($errores);
}
