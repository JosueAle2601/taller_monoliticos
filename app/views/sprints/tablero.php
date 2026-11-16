<?php

$esc = function ($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
};

//Agrupar las historias por estado
$estados = [
    'nueva' => 'Nuevas',
    'activa' => 'Activas',
    'finalizada' => 'Finalizadas',
    'impedimento' => 'En impedimento'
];

$historiasPorEstado = [];

foreach ($estados as $estado => $nombre) {
    $historiasPorEstado[$estado] = [];
}

foreach ($historias as $historia) {

    if (isset($historiasPorEstado[$historia['estado']])) {
        $historiasPorEstado[$historia['estado']][] = $historia;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tablero del Sprint</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <header>
        <h1>Tablero del Sprint</h1>

        <h2><?= $esc($sprint['nombre']) ?></h2>

        <p>
            <strong>Inicio:</strong>
            <?= $esc($sprint['fecha_inicio']) ?>
        </p>

        <p>
            <strong>Finalización:</strong>
            <?= $esc($sprint['fecha_fin']) ?>
        </p>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="index.php?modulo=sprints">Volver a sprints</a>
        <a href="index.php?modulo=informes">Informes</a>
    </nav>

    <main>

        <section>
            <h2>Historias de usuario</h2>

            <?php if (empty($historias)): ?>

                <p>
                    Este sprint todavía no tiene historias asignadas.
                </p>

            <?php else: ?>

                <p>
                    Total de historias:
                    <?= count($historias) ?>
                </p>

                <?php foreach ($estados as $estado => $nombre): ?>

                    <section>

                        <h3>
                            <?= $nombre ?>
                            (<?= count($historiasPorEstado[$estado]) ?>)
                        </h3>

                        <?php if (empty($historiasPorEstado[$estado])): ?>

                            <p>No hay historias en esta categoría.</p>

                        <?php else: ?>

                            <?php foreach ($historiasPorEstado[$estado] as $historia): ?>

                                <article>

                                    <h4>
                                        <?= $esc($historia['titulo']) ?>
                                    </h4>

                                    <p>
                                        <?= nl2br($esc($historia['descripcion'])) ?>
                                    </p>

                                    <p>
                                        <strong>Responsable:</strong>
                                        <?= $esc($historia['responsable']) ?>
                                    </p>

                                    <p>
                                        <strong>Puntos:</strong>
                                        <?= (int)$historia['puntos'] ?>
                                    </p>

                                    <p>
                                        <strong>Fecha de creación:</strong>
                                        <?= $esc($historia['fecha_creacion']) ?>
                                    </p>

                                    <?php if (!empty($historia['fecha_finalizacion'])): ?>

                                        <p>
                                            <strong>Fecha de finalización:</strong>
                                            <?= $esc($historia['fecha_finalizacion']) ?>
                                        </p>

                                    <?php endif; ?>

                                </article>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </section>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    </main>

    <footer>
        <p>Gestor de Historias de Usuario</p>
    </footer>

</body>
</html>
