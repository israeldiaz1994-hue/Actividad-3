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

$datos = [
    'nombres' => trim($_POST['nombres'] ?? ''),
    'apellidos' => trim($_POST['apellidos'] ?? ''),
    'correo' => trim($_POST['correo'] ?? ''),
    'telefono' => trim($_POST['telefono'] ?? ''),
    'carrera' => trim($_POST['carrera'] ?? ''),
];
$errores = validar_estudiante($datos);
if ($errores) {
    flash('error', implode(' ', $errores));
    header('Location: editar.php?id=' . $id);
    exit;
}

try {
    $sql = 'UPDATE estudiantes SET nombres=:nombres, apellidos=:apellidos,
            correo=:correo, telefono=:telefono, carrera=:carrera WHERE id=:id';
    $stmt = $conexion->prepare($sql);
    $datos['id'] = $id;
    $stmt->execute($datos);
    flash('exito', 'Información del estudiante actualizada correctamente.');
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    error_log('Error al actualizar estudiante: ' . $e->getMessage());
    if ($e->getCode() === '23000') {
        flash('error', 'Ese correo ya pertenece a otro estudiante.');
        header('Location: editar.php?id=' . $id);
        exit;
    }
    http_response_code(500);
    exit('No fue posible actualizar el registro.');
}
