<?php
$esc = function ($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
};

$mensajesOk = [
    'creado' => 'Sprint creado correctamente.',
    'actualizado' => 'Sprint actualizado correctamente.',
    'eliminado' => 'Sprint eliminado correctamente.'
];

$mensajesError = [
    'campos' => 'Completa todos los campos obligatorios del sprint.',
    'fechas' => 'La fecha de inicio no puede ser posterior a la fecha de finalización.',
    'tiene_historias' => 'No se puede eliminar este sprint porque tiene historias de usuario asignadas.'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SprintFlow - Gestión de Sprints</title>
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
                <h1>Gestión de <span>Sprints</span></h1>
                <p>Administra los periodos de trabajo y accede a sus tableros Kanban.</p>
            </div>
            <div class="top-badges">
                <span>Gestiona &bull; Organiza &bull; Avanza</span>
                <span class="version-pill"><span class="version-dot"></span> v1.0</span>
            </div>
        </header>

        <?php if (isset($_GET['ok']) && isset($mensajesOk[$_GET['ok']])): ?>
            <div class="alert-banner alert-success"><?= $esc($mensajesOk[$_GET['ok']]) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && isset($mensajesError[$_GET['error']])): ?>
            <div class="alert-banner alert-error"><?= $esc($mensajesError[$_GET['error']]) ?></div>
        <?php endif; ?>

        <!-- Crear nuevo sprint -->
        <section class="glass-panel">
            <div class="panel-header">
                <h2 class="panel-title">Crear nuevo sprint</h2>
            </div>

            <form action="index.php?modulo=sprints&accion=crear" method="POST" class="sprint-create-form">
                <div class="form-group">
                    <label for="nombre">Nombre del sprint:</label>
                    <input type="text" id="nombre" name="nombre" maxlength="100" required
                           placeholder="Ej: Sprint 1 - Módulo Base">
                </div>

                <div class="form-group">
                    <label for="fecha_inicio">Fecha de inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin">Fecha de finalización:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" required>
                </div>

                <div class="form-group sprint-submit-group">
                    <button type="submit" class="btn-primary">+ Crear sprint</button>
                </div>
            </form>
        </section>

        <!-- Lista de sprints -->
        <section class="glass-panel">
            <div class="panel-header">
                <h2 class="panel-title">Sprints registrados</h2>
                <span class="panel-counter">Total: <strong><?= count($sprints ?? []) ?></strong></span>
            </div>

            <?php if (empty($sprints)): ?>
                <p class="empty-message">No hay sprints registrados todavía.</p>
            <?php else: ?>
                <div class="sprints-grid">
                    <?php foreach ($sprints as $sprint): ?>
                        <article class="sprint-card">
                            <div class="sprint-card-top">
                                <div>
                                    <h3><?= $esc($sprint['nombre']) ?></h3>
                                    <p class="sprint-dates">
                                        <span>Inicio: <strong><?= $esc($sprint['fecha_inicio']) ?></strong></span>
                                        &bull;
                                        <span>Fin: <strong><?= $esc($sprint['fecha_fin']) ?></strong></span>
                                    </p>
                                </div>

                                <div class="sprint-card-actions">
                                    <a href="index.php?modulo=sprints&accion=tablero&id=<?= (int)$sprint['id'] ?>" class="btn-primary btn-sm">
                                        Ver tablero
                                    </a>

                                    <form action="index.php?modulo=sprints&accion=eliminar" method="POST" class="inline-form"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este sprint?');">
                                        <input type="hidden" name="id" value="<?= (int)$sprint['id'] ?>">
                                        <button type="submit" class="btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </div>

                            <details class="sprint-edit-details">
                                <summary class="sprint-edit-toggle">Editar datos del sprint</summary>
                                <form action="index.php?modulo=sprints&accion=actualizar" method="POST" class="sprint-edit-form">
                                    <input type="hidden" name="id" value="<?= (int)$sprint['id'] ?>">

                                    <div class="form-group">
                                        <label>Nombre:</label>
                                        <input type="text" name="nombre" value="<?= $esc($sprint['nombre']) ?>" maxlength="100" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Fecha de inicio:</label>
                                        <input type="date" name="fecha_inicio" value="<?= $esc($sprint['fecha_inicio']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Fecha de finalización:</label>
                                        <input type="date" name="fecha_fin" value="<?= $esc($sprint['fecha_fin']) ?>" required>
                                    </div>

                                    <div class="form-group sprint-submit-group">
                                        <button type="submit" class="btn-secondary btn-sm">Guardar cambios</button>
                                    </div>
                                </form>
                            </details>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>