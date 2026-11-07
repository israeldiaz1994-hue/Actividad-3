<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/conexion.php';

$mensaje = obtener_flash();
try {
    $consulta = $conexion->query(
        'SELECT id, nombres, apellidos, correo, telefono, carrera, fecha_creacion
         FROM estudiantes ORDER BY id DESC'
    );
    $estudiantes = $consulta->fetchAll();
} catch (PDOException $e) {
    error_log('Error al consultar estudiantes: ' . $e->getMessage());
    http_response_code(500);
    exit('No fue posible consultar los registros.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Registro de Estudiantes</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<main class="contenedor">
    <header class="encabezado">
        <div>
            <p class="etiqueta">SISTEMA WEB · PHP + PDO + MYSQL</p>
            <h1>Registro de estudiantes</h1>
            <p class="subtitulo">Administre la información de los estudiantes registrados.</p>
        </div>
        <a class="boton" href="crear.php">+ Nuevo estudiante</a>
    </header>

    <?php if ($mensaje): ?>
        <div class="alerta <?= e($mensaje['tipo']) ?>" role="status"><?= e($mensaje['mensaje']) ?></div>
    <?php endif; ?>

    <section class="panel">
        <div class="panel-titulo">
            <h2>Estudiantes registrados</h2>
            <span class="contador"><?= count($estudiantes) ?> registro(s)</span>
        </div>
        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Nombres</th><th>Apellidos</th><th>Correo</th>
                        <th>Teléfono</th><th>Carrera</th><th>Fecha de creación</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$estudiantes): ?>
                    <tr><td colspan="8" class="vacio">Todavía no hay estudiantes registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($estudiantes as $estudiante): ?>
                    <tr>
                        <td><?= e($estudiante['id']) ?></td>
                        <td><?= e($estudiante['nombres']) ?></td>
                        <td><?= e($estudiante['apellidos']) ?></td>
                        <td><?= e($estudiante['correo']) ?></td>
                        <td><?= e($estudiante['telefono']) ?></td>
                        <td><?= e($estudiante['carrera']) ?></td>
                        <td><?= e($estudiante['fecha_creacion']) ?></td>
                        <td class="acciones">
                            <a class="enlace-accion" href="editar.php?id=<?= e($estudiante['id']) ?>">Editar</a>
                            <form action="eliminar.php" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este registro?');">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= e($estudiante['id']) ?>">
                                <button class="boton-eliminar" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <footer>Proyecto académico · Actividad Integradora 3</footer>
</main>
</body>
</html>
