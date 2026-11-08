<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
validar_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('Identificador no válido.');
}

try {
    $stmt = $conexion->prepare('DELETE FROM estudiantes WHERE id = :id');
    $stmt->execute(['id' => $id]);
    flash('exito', $stmt->rowCount() ? 'Estudiante eliminado correctamente.' : 'El registro ya no existe.');
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    error_log('Error al eliminar estudiante: ' . $e->getMessage());
    http_response_code(500);
    exit('No fue posible eliminar el registro.');
}
