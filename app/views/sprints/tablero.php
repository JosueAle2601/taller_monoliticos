<?php
$esc = function ($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
};

$estados = [
    'nueva'       => ['titulo' => 'Nuevas',         'clase' => 'col-nueva'],
    'activa'      => ['titulo' => 'Activas',        'clase' => 'col-activa'],
    'finalizada'  => ['titulo' => 'Finalizadas',    'clase' => 'col-finalizada'],
    'impedimento' => ['titulo' => 'En impedimento', 'clase' => 'col-impedimento']
];

$historiasPorEstado = [];
foreach ($estados as $estado => $info) {
    $historiasPorEstado[$estado] = [];
}

$puntosTotales = 0;
foreach ($historias as $historia) {
    $puntosTotales += (int)$historia['puntos'];
    if (isset($historiasPorEstado[$historia['estado']])) {
        $historiasPorEstado[$historia['estado']][] = $historia;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SprintFlow - Tablero: <?= $esc($sprint['nombre']) ?></title>
    <link rel="stylesheet" href="css/styles.css?v=4">
</head>
<body>

    <aside class="sidebar">
        <div>
            <div class="brand">
                <div class="brand-logo">
                    <svg viewBox="0 0 48 48" width="44" height="44" fill="none">
                        <path d="M10 10L22 24L10 38" stroke="#ffb02e" stroke-width="6.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M38 10L26 24L38 38" stroke="#ff6b1a" stroke-width="6.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="brand-name">Sprint<span>Flow</span></div>
            </div>

            <nav class="nav-menu">
                <a href="index.php" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Inicio
                </a>
                <a href="index.php?modulo=historias" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Historias
                </a>
                <a href="index.php?modulo=sprints" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Sprints
                </a>
                <a href="index.php?modulo=informes" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Informes
                </a>
            </nav>
        </div>

        <footer class="sidebar-footer">
            <p class="footer-note">Gestor de Historias de Usuario</p>
        </footer>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <div class="page-title">
                <h1>Tablero: <span><?= $esc($sprint['nombre']) ?></span></h1>
                <p>
                    Inicio: <strong><?= $esc($sprint['fecha_inicio']) ?></strong> &bull;
                    Finalización: <strong><?= $esc($sprint['fecha_fin']) ?></strong> &bull;
                    Historias: <strong><?= count($historias) ?></strong> (<?= $puntosTotales ?> pts)
                </p>
            </div>
            <div class="sprint-card-actions">
                <a href="index.php?modulo=historias&accion=crear" class="btn-primary">+ Nueva historia</a>
                <a href="index.php?modulo=sprints" class="btn-secondary">&larr; Volver a sprints</a>
            </div>
        </header>

        <?php if (empty($historias)): ?>
            <section class="glass-panel">
                <p class="empty-message">Este sprint todavía no tiene historias asignadas.</p>
            </section>
        <?php else: ?>
            <section class="kanban-board">
                <?php foreach ($estados as $estado => $info): ?>
                    <div class="glass-panel kanban-column <?= $info['clase'] ?>">
                        <div class="kanban-column-header">
                            <h3>
                                <span class="status-dot"></span>
                                <?= $info['titulo'] ?>
                            </h3>
                            <span class="kanban-count"><?= count($historiasPorEstado[$estado]) ?></span>
                        </div>

                        <div class="kanban-cards">
                            <?php if (empty($historiasPorEstado[$estado])): ?>
                                <p class="kanban-empty">Sin historias</p>
                            <?php else: ?>
                                <?php foreach ($historiasPorEstado[$estado] as $historia): ?>
                                    <article class="kanban-card">
                                        <div class="kanban-card-header">
                                            <h4><?= $esc($historia['titulo']) ?></h4>
                                            <span class="points-badge"><?= (int)$historia['puntos'] ?> pts</span>
                                        </div>

                                        <p class="kanban-card-desc">
                                            <?= nl2br($esc($historia['descripcion'])) ?>
                                        </p>

                                        <div class="kanban-card-meta">
                                            <span><strong>Resp:</strong> <?= $esc($historia['responsable']) ?></span>
                                            <span>Creada: <?= $esc($historia['fecha_creacion']) ?></span>
                                            <?php if (!empty($historia['fecha_finalizacion'])): ?>
                                                <span>Fin: <?= $esc($historia['fecha_finalizacion']) ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="kanban-card-footer">
                                            <a href="index.php?modulo=historias&accion=editar&id=<?= (int)$historia['id'] ?>" class="kanban-edit-link">
                                                Editar estado / datos &rarr;
                                            </a>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>

</body>
</html>