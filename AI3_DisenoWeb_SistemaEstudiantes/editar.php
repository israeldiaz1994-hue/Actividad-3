<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/conexion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('Identificador no válido.');
}

try {
    $stmt = $conexion->prepare('SELECT * FROM estudiantes WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $estudiante = $stmt->fetch();
    if (!$estudiante) {
        http_response_code(404);
        exit('Estudiante no encontrado.');
    }
} catch (PDOException $e) {
    error_log('Error al cargar estudiante: ' . $e->getMessage());
    http_response_code(500);
    exit('No fue posible cargar el registro.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar estudiante</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<main class="formulario-pagina">
    <a class="volver" href="index.php">← Volver a los registros</a>
    <section class="panel formulario-panel">
        <p class="etiqueta">ACTUALIZAR INFORMACIÓN</p>
        <h1>Editar estudiante</h1>
        <form action="actualizar.php" method="POST" class="formulario">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= e($estudiante['id']) ?>">
            <label>Nombres<input type="text" name="nombres" maxlength="100" value="<?= e($estudiante['nombres']) ?>" required></label>
            <label>Apellidos<input type="text" name="apellidos" maxlength="100" value="<?= e($estudiante['apellidos']) ?>" required></label>
            <label>Correo electrónico<input type="email" name="correo" maxlength="150" value="<?= e($estudiante['correo']) ?>" required></label>
            <label>Teléfono<input type="tel" name="telefono" maxlength="20" value="<?= e($estudiante['telefono']) ?>" required></label>
            <label>Carrera<input type="text" name="carrera" maxlength="100" value="<?= e($estudiante['carrera']) ?>" required></label>
            <button class="boton ancho" type="submit">Actualizar estudiante</button>
        </form>
    </section>
</main>
</body>
</html>
