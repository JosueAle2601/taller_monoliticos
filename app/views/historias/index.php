<?php
$esc = function ($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SprintFlow - Historias de Usuario</title>
    <link rel="stylesheet" href="css/styles.css?v=3">
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
                <a href="index.php?modulo=historias" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Historias
                </a>
                <a href="index.php?modulo=sprints" class="nav-item">
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
                <h1>Gestión de <span>Historias</span></h1>
                <p>Administra, filtra y actualiza las historias de usuario del proyecto.</p>
            </div>
            <div class="top-badges">
                <span>Gestiona &bull; Organiza &bull; Avanza</span>
                <span class="version-pill"><span class="version-dot"></span> v1.0</span>
            </div>
        </header>

        <section class="glass-panel toolbar-panel">
            <form method="GET" action="index.php" class="filter-form">
                <input type="hidden" name="modulo" value="historias">
                <label for="sprint_id" class="filter-label">Filtrar por Sprint:</label>
                <select name="sprint_id" id="sprint_id" class="sprint-select">
                    <option value="">Todos los Sprints</option>
                    <?php if (!empty($sprints)): ?>
                        <?php foreach ($sprints as $sp): ?>
                            <option value="<?= (int)$sp['id'] ?>" <?= (isset($_GET['sprint_id']) && $_GET['sprint_id'] == $sp['id']) ? 'selected' : '' ?>>
                                <?= $esc($sp['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <button type="submit" class="btn-primary">Filtrar</button>
            </form>

            <a href="index.php?modulo=historias&accion=crear" class="btn-primary">
                + Crear nueva historia
            </a>
        </section>

        <section class="glass-panel">
            <div class="panel-header">
                <h2 class="panel-title">Historias registradas</h2>
                <span class="panel-counter">
                    Total: <strong><?= count($historias ?? []) ?></strong>
                </span>
            </div>

            <?php if (empty($historias)): ?>
                <p class="empty-message">
                    No hay historias registradas todavía.
                </p>
            <?php else: ?>
                <div class="table-container">
                    <table class="stories-table">
                        <thead>
                            <tr>
                                <th>Historia</th>
                                <th>Responsable</th>
                                <th>Sprint</th>
                                <th>Puntos</th>
                                <th>Estado</th>
                                <th>Fechas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historias as $h): ?>
                                <tr>
                                    <td>
                                        <strong><?= $esc($h['titulo']) ?></strong>
                                        <div class="story-desc">
                                            <?= $esc($h['descripcion']) ?>
                                        </div>
                                    </td>
                                    <td><?= $esc($h['responsable']) ?></td>
                                    <td><?= $esc($h['sprint_nombre']) ?></td>
                                    <td><strong><?= (int)$h['puntos'] ?> pts</strong></td>
                                    <td>
                                        <?php if ($h['estado'] === 'finalizada'): ?>
                                            <span class="badge-status badge-finalizada">&#9679; Finalizada</span>
                                        <?php elseif ($h['estado'] === 'activa'): ?>
                                            <span class="badge-status badge-activa">&#9654; Activa</span>
                                        <?php elseif ($h['estado'] === 'nueva'): ?>
                                            <span class="badge-status badge-nueva">&#10010; Nueva</span>
                                        <?php else: ?>
                                            <span class="badge-status badge-impedimento">&#9888; Impedimento</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="story-dates">
                                        Inicio: <?= $esc($h['fecha_creacion']) ?><br>
                                        <?php if (!empty($h['fecha_finalizacion'])): ?>
                                            Fin: <?= $esc($h['fecha_finalizacion']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-links">
                                            <a href="index.php?modulo=historias&accion=editar&id=<?= (int)$h['id'] ?>" class="btn-icon" title="Editar">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </a>

                                            <form action="index.php?modulo=historias&accion=eliminar" method="POST" class="inline-form">
                                                <input type="hidden" name="id" value="<?= (int)$h['id'] ?>">
                                                <button type="submit" class="btn-icon delete" title="Eliminar">
                                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>