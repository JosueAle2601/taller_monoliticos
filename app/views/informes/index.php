<?php

$esc = function ($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
};

//Evitar valores nulos
$total = (int)($general['total'] ?? 0);
$finalizadas = (int)($general['finalizadas'] ?? 0);
$pendientes = (int)($general['pendientes'] ?? 0);
$impedimentos = (int)($general['impedimentos'] ?? 0);

//Calcular porcentaje general
$porcentaje = $total > 0
    ? round(($finalizadas / $total) * 100, 2)
    : 0;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informes del Proyecto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <header>
        <h1>Informes del Proyecto</h1>

        <p>
            Consulta el progreso de las historias de usuario
            y el rendimiento de cada responsable.
        </p>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="index.php?modulo=historias">Historias</a>
        <a href="index.php?modulo=sprints">Sprints</a>
        <a href="index.php?modulo=informes">Informes</a>
    </nav>

    <main>

        <!-- Resumen general -->

        <section>

            <h2>Resumen general</h2>

            <p>
                <strong>Total de historias:</strong>
                <?= $total ?>
            </p>

            <p>
                <strong>Finalizadas:</strong>
                <?= $finalizadas ?>
            </p>

            <p>
                <strong>Pendientes:</strong>
                <?= $pendientes ?>
            </p>

            <p>
                <strong>Con impedimentos:</strong>
                <?= $impedimentos ?>
            </p>

            <p>
                <strong>Porcentaje de finalización:</strong>
                <?= $porcentaje ?>%
            </p>

        </section>

        <hr>

        <!-- Informe por responsable -->

        <section>

            <h2>Informe por responsable</h2>

            <?php if (empty($responsables)): ?>

                <p>No hay historias registradas.</p>

            <?php else: ?>

                <table border="1">

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

                            $totalResponsable = (int)$responsable['total'];
                            $finalizadasResponsable = (int)$responsable['finalizadas'];

                            $progresoResponsable = $totalResponsable > 0
                                ? round(
                                    ($finalizadasResponsable / $totalResponsable) * 100,
                                    2
                                )
                                : 0;

                            ?>

                            <tr>

                                <td>
                                    <?= $esc($responsable['responsable']) ?>
                                </td>

                                <td>
                                    <?= $totalResponsable ?>
                                </td>

                                <td>
                                    <?= $finalizadasResponsable ?>
                                </td>

                                <td>
                                    <?= (int)$responsable['pendientes'] ?>
                                </td>

                                <td>
                                    <?= (int)$responsable['impedimentos'] ?>
                                </td>

                                <td>
                                    <?= $progresoResponsable ?>%
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </section>

        <hr>

        <!-- Informe por sprint -->

        <section>

            <h2>Informe por sprint</h2>

            <?php if (empty($sprints)): ?>

                <p>No hay sprints registrados.</p>

            <?php else: ?>

                <table border="1">

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
                            $finalizadasSprint = (int)$sprint['finalizadas'];

                            $progresoSprint = $totalSprint > 0
                                ? round(
                                    ($finalizadasSprint / $totalSprint) * 100,
                                    2
                                )
                                : 0;

                            ?>

                            <tr>

                                <td>
                                    <?= $esc($sprint['nombre']) ?>
                                </td>

                                <td>
                                    <?= $totalSprint ?>
                                </td>

                                <td>
                                    <?= $finalizadasSprint ?>
                                </td>

                                <td>
                                    <?= (int)$sprint['pendientes'] ?>
                                </td>

                                <td>
                                    <?= (int)$sprint['impedimentos'] ?>
                                </td>

                                <td>
                                    <?= $progresoSprint ?>%
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </section>

    </main>

    <footer>
        <p>Gestor de Historias de Usuario</p>
    </footer>

</body>
</html>
