<?php
$esc = function ($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
};

$total = (int)($general['total'] ?? 0);
$finalizadas = (int)($general['finalizadas'] ?? 0);
$pendientes = (int)($general['pendientes'] ?? 0);
$impedimentos = (int)($general['impedimentos'] ?? 0);

$porcentaje = $total > 0
    ? round(($finalizadas / $total) * 100, 1)
    : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SprintFlow - Informes del Proyecto</title>
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
                <a href="index.php?modulo=sprints" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Sprints
                </a>
                <a href="index.php?modulo=informes" class="nav-item active">
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
                <h1>Informes del <span>Proyecto</span></h1>
                <p>Consulta el progreso general, por responsable y por sprint.</p>
            </div>
            <div class="top-badges">
                <span>Gestiona &bull; Organiza &bull; Avanza</span>
                <span class="version-pill"><span class="version-dot"></span> v1.0</span>
            </div>
        </header>

        <!-- Tarjetas KPI generales -->
        <section class="kpi-grid">
            <article class="glass-panel kpi-card">
                <span class="kpi-label">Total de historias</span>
                <strong class="kpi-value"><?= $total ?></strong>
            </article>

            <article class="glass-panel kpi-card kpi-finalizada">
                <span class="kpi-label">Finalizadas</span>
                <strong class="kpi-value"><?= $finalizadas ?></strong>
            </article>

            <article class="glass-panel kpi-card kpi-activa">
                <span class="kpi-label">Pendientes</span>
                <strong class="kpi-value"><?= $pendientes ?></strong>
            </article>

            <article class="glass-panel kpi-card kpi-impedimento">
                <span class="kpi-label">Con impedimentos</span>
                <strong class="kpi-value"><?= $impedimentos ?></strong>
            </article>
        </section>

        <!-- Barra de progreso general -->
        <section class="glass-panel">
            <div class="panel-header">
                <h2 class="panel-title">Porcentaje de finalización general</h2>
                <span class="progress-percent-big"><?= $porcentaje ?>%</span>
            </div>
            <div class="progress-track progress-track-lg">
                <div class="progress-fill" style="width: <?= $porcentaje ?>%;"></div>
            </div>
        </section>

        <!-- Informe por responsable -->
        <section class="glass-panel">
            <div class="panel-header">
                <h2 class="panel-title">Informe por responsable</h2>
                <span class="panel-counter">Integrantes: <strong><?= count($responsables ?? []) ?></strong></span>
            </div>

            <?php if (empty($responsables)): ?>
                <p class="empty-message">No hay historias registradas.</p>
            <?php else: ?>
                <div class="table-container">
                    <table class="stories-table">
                        <thead>
                            <tr>
                                <th>Responsable</th>
                                <th>Total</th>
                                <th>Finalizadas</th>
                                <th>Pendientes</th>
                                <th>Impedimentos</th>
                                <th>Progreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($responsables as $responsable): ?>
                                <?php
                                $totalResp = (int)$responsable['total'];
                                $finResp = (int)$responsable['finalizadas'];
                                $progresoResp = $totalResp > 0 ? round(($finResp / $totalResp) * 100, 1) : 0;
                                ?>
                                <tr>
                                    <td><strong><?= $esc($responsable['responsable']) ?></strong></td>
                                    <td><?= $totalResp ?></td>
                                    <td><span class="badge-status badge-finalizada"><?= $finResp ?></span></td>
                                    <td><span class="badge-status badge-activa"><?= (int)$responsable['pendientes'] ?></span></td>
                                    <td><span class="badge-status badge-impedimento"><?= (int)$responsable['impedimentos'] ?></span></td>
                                    <td class="progress-cell">
                                        <div class="progress-wrapper">
                                            <div class="progress-track">
                                                <div class="progress-fill" style="width: <?= $progresoResp ?>%;"></div>
                                            </div>
                                            <span><?= $progresoResp ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <!-- Informe por sprint -->
        <section class="glass-panel">
            <div class="panel-header">
                <h2 class="panel-title">Informe por sprint</h2>
                <span class="panel-counter">Sprints: <strong><?= count($sprints ?? []) ?></strong></span>
            </div>

            <?php if (empty($sprints)): ?>
                <p class="empty-message">No hay sprints registrados.</p>
            <?php else: ?>
                <div class="table-container">
                    <table class="stories-table">
                        <thead>
                            <tr>
                                <th>Sprint</th>
                                <th>Total</th>
                                <th>Finalizadas</th>
                                <th>Pendientes</th>
                                <th>Impedimentos</th>
                                <th>Progreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sprints as $sprint): ?>
                                <?php
                                $totalSprint = (int)$sprint['total'];
                                $finSprint = (int)$sprint['finalizadas'];
                                $progresoSprint = $totalSprint > 0 ? round(($finSprint / $totalSprint) * 100, 1) : 0;
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= $esc($sprint['nombre']) ?></strong>
                                        <div class="story-desc">
                                            <?= $esc($sprint['fecha_inicio']) ?> &rarr; <?= $esc($sprint['fecha_fin']) ?>
                                        </div>
                                    </td>
                                    <td><?= $totalSprint ?></td>
                                    <td><span class="badge-status badge-finalizada"><?= $finSprint ?></span></td>
                                    <td><span class="badge-status badge-activa"><?= (int)$sprint['pendientes'] ?></span></td>
                                    <td><span class="badge-status badge-impedimento"><?= (int)$sprint['impedimentos'] ?></span></td>
                                    <td class="progress-cell">
                                        <div class="progress-wrapper">
                                            <div class="progress-track">
                                                <div class="progress-fill" style="width: <?= $progresoSprint ?>%;"></div>
                                            </div>
                                            <span><?= $progresoSprint ?>%</span>
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