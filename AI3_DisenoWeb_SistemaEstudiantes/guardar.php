<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: crear.php');
    exit;
}
validar_csrf();

$datos = [
    'nombres' => trim($_POST['nombres'] ?? ''),
    'apellidos' => trim($_POST['apellidos'] ?? ''),
    'correo' => trim($_POST['correo'] ?? ''),
    'telefono' => trim($_POST['telefono'] ?? ''),
    'carrera' => trim($_POST['carrera'] ?? ''),
];

$errores = validar_estudiante($datos);
if ($errores) {
    $_SESSION['errores'] = $errores;
    flash('error', 'Revise la información ingresada.');
    header('Location: crear.php');
    exit;
}

try {
    $sql = 'INSERT INTO estudiantes (nombres, apellidos, correo, telefono, carrera)
            VALUES (:nombres, :apellidos, :correo, :telefono, :carrera)';
    $stmt = $conexion->prepare($sql);
    $stmt->execute($datos);
    flash('exito', 'Estudiante registrado correctamente.');
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    error_log('Error al insertar estudiante: ' . $e->getMessage());
    if ($e->getCode() === '23000') {
        flash('error', 'Ese correo ya se encuentra registrado. Utilice otro correo.');
        header('Location: crear.php');
        exit;
    }
    http_response_code(500);
    exit('No fue posible guardar el registro. Inténtelo nuevamente.');
}
