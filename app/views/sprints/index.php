<?php
$esc = function ($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Sprints</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <header>
        <h1>Gestión de Sprints</h1>
        <p>Administra los periodos de trabajo del proyecto.</p>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="index.php?modulo=historias">Historias</a>
        <a href="index.php?modulo=sprints">Sprints</a>
        <a href="index.php?modulo=informes">Informes</a>
    </nav>

    <main>

        <section>
            <h2>Crear nuevo sprint</h2>

            <form action="index.php?modulo=sprints&accion=crear"
                  method="POST">

                <div>
                    <label for="nombre">Nombre del sprint:</label>
                    <input type="text" id="nombre"
                           name="nombre" maxlength="100" required>
                </div>

                <div>
                    <label for="fecha_inicio">Fecha de inicio:</label>
                    <input type="date" id="fecha_inicio"
                           name="fecha_inicio" required>
                </div>

                <div>
                    <label for="fecha_fin">Fecha de finalización:</label>
                    <input type="date" id="fecha_fin"
                           name="fecha_fin" required>
                </div>

                <button type="submit">Crear sprint</button>

            </form>
        </section>

        <section>
            <h2>Sprints registrados</h2>

            <?php if (empty($sprints)): ?>

                <p>No hay sprints registrados todavía.</p>

            <?php else: ?>

                <?php foreach ($sprints as $sprint): ?>

                    <article>

                        <h3>
                            <?= $esc($sprint['nombre']) ?>
                        </h3>

                        <p>
                            <strong>Fecha de inicio:</strong>
                            <?= $esc($sprint['fecha_inicio']) ?>
                        </p>

                        <p>
                            <strong>Fecha de finalización:</strong>
                            <?= $esc($sprint['fecha_fin']) ?>
                        </p>

                        <a href="index.php?modulo=sprints&accion=tablero&id=<?= (int)$sprint['id'] ?>">
                            Ver tablero
                        </a>

                        <h4>Editar sprint</h4>

                        <form action="index.php?modulo=sprints&accion=actualizar"
                              method="POST">

                            <input type="hidden" name="id"
                                   value="<?= (int)$sprint['id'] ?>">

                            <div>
                                <label>Nombre:</label>
                                <input type="text" name="nombre"
                                       value="<?= $esc($sprint['nombre']) ?>"
                                       maxlength="100" required>
                            </div>

                            <div>
                                <label>Fecha de inicio:</label>
                                <input type="date" name="fecha_inicio"
                                       value="<?= $esc($sprint['fecha_inicio']) ?>"
                                       required>
                            </div>

                            <div>
                                <label>Fecha de finalización:</label>
                                <input type="date" name="fecha_fin"
                                       value="<?= $esc($sprint['fecha_fin']) ?>"
                                       required>
                            </div>

                            <button type="submit">
                                Guardar cambios
                            </button>

                        </form>

                        <form action="index.php?modulo=sprints&accion=eliminar"
                              method="POST">

                            <input type="hidden" name="id"
                                   value="<?= (int)$sprint['id'] ?>">

                            <button type="submit">
                                Eliminar sprint
                            </button>

                        </form>

                    </article>

                    <hr>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    </main>

    <footer>
        <p>Gestor de Historias de Usuario</p>
    </footer>

</body>
</html>
