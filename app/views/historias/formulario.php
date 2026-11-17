<?php
$esc = function ($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
};
$esEdicion = !empty($historia);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SprintFlow - <?= $esEdicion ? 'Editar Historia' : 'Nueva Historia' ?></title>
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
                <h1><?= $esEdicion ? 'Editar' : 'Nueva' ?> <span>Historia</span></h1>
                <p>Completa los datos de la historia de usuario para asignarla al sprint.</p>
            </div>
            <a href="index.php?modulo=historias" class="btn-secondary">&larr; Volver a historias</a>
        </header>

        <section class="glass-panel form-panel">
            <div class="panel-header">
                <h2 class="panel-title">Detalles de la Historia</h2>
            </div>

            <form method="POST" action="index.php?modulo=historias&accion=<?= $esEdicion ? 'actualizar' : 'guardar' ?>" class="story-form">
                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= (int)$historia['id'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="titulo">Título de la historia:</label>
                    <input type="text" id="titulo" name="titulo" required maxlength="150"
                           placeholder="Ej: Como usuario, quiero registrar una tarea"
                           value="<?= $esEdicion ? $esc($historia['titulo']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="3" required
                              placeholder="Describe la funcionalidad esperada..."><?= $esEdicion ? $esc($historia['descripcion']) : '' ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="responsable">Responsable:</label>
                        <input type="text" id="responsable" name="responsable" required maxlength="100"
                               placeholder="Ej: Ana Torres"
                               value="<?= $esEdicion ? $esc($historia['responsable']) : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="sprint_id">Sprint asignado:</label>
                        <select id="sprint_id" name="sprint_id" required>
                            <option value="">Selecciona un sprint...</option>
                            <?php foreach ($sprints as $sp): ?>
                                <option value="<?= (int)$sp['id'] ?>" <?= ($esEdicion && $historia['sprint_id'] == $sp['id']) ? 'selected' : '' ?>>
                                    <?= $esc($sp['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="puntos">Puntos de complejidad:</label>
                        <input type="number" id="puntos" name="puntos" min="1" max="100" required
                               value="<?= $esEdicion ? (int)$historia['puntos'] : 3 ?>">
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <select id="estado" name="estado" required>
                            <option value="nueva" <?= ($esEdicion && $historia['estado'] === 'nueva') ? 'selected' : '' ?>>Nueva</option>
                            <option value="activa" <?= ($esEdicion && $historia['estado'] === 'activa') ? 'selected' : '' ?>>Activa</option>
                            <option value="finalizada" <?= ($esEdicion && $historia['estado'] === 'finalizada') ? 'selected' : '' ?>>Finalizada</option>
                            <option value="impedimento" <?= ($esEdicion && $historia['estado'] === 'impedimento') ? 'selected' : '' ?>>En impedimento</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_creacion">Fecha de creación:</label>
                        <input type="date" id="fecha_creacion" name="fecha_creacion" required
                               value="<?= $esEdicion ? $esc($historia['fecha_creacion']) : date('Y-m-d') ?>">
                    </div>

                    <div class="form-group">
                        <label for="fecha_finalizacion">Fecha de finalización (opcional):</label>
                        <input type="date" id="fecha_finalizacion" name="fecha_finalizacion"
                               value="<?= ($esEdicion && !empty($historia['fecha_finalizacion'])) ? $esc($historia['fecha_finalizacion']) : '' ?>">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <?= $esEdicion ? 'Guardar cambios' : 'Crear historia' ?>
                    </button>
                    <a href="index.php?modulo=historias" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </section>
    </main>

</body>
</html>
