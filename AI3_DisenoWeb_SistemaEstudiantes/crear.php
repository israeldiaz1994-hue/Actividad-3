<?php
require_once __DIR__ . '/funciones.php';
$valores = ['nombres' => '', 'apellidos' => '', 'correo' => '', 'telefono' => '', 'carrera' => ''];
$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar estudiante</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<main class="formulario-pagina">
    <a class="volver" href="index.php">← Volver a los registros</a>
    <section class="panel formulario-panel">
        <p class="etiqueta">NUEVO REGISTRO</p>
        <h1>Registrar estudiante</h1>
        <p class="subtitulo">Complete todos los campos solicitados.</p>
        <?php if ($errores): ?>
            <div class="alerta error"><ul><?php foreach ($errores as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form action="guardar.php" method="POST" class="formulario">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <label>Nombres<input type="text" name="nombres" maxlength="100" required autocomplete="given-name"></label>
            <label>Apellidos<input type="text" name="apellidos" maxlength="100" required autocomplete="family-name"></label>
            <label>Correo electrónico<input type="email" name="correo" maxlength="150" required autocomplete="email"></label>
            <label>Teléfono<input type="tel" name="telefono" maxlength="20" required autocomplete="tel"></label>
            <label>Carrera<input type="text" name="carrera" maxlength="100" required></label>
            <button class="boton ancho" type="submit">Guardar estudiante</button>
        </form>
    </section>
</main>
</body>
</html>
