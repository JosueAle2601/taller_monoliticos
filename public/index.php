<?php

require_once __DIR__ . "/../config/database.php";

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("No se pudo conectar con la base de datos.");
}

$modulo = $_GET["modulo"] ?? "inicio";
$accion = $_GET["accion"] ?? "index";

switch ($modulo) {

    case "sprints":

        require_once __DIR__ . "/../app/Controllers/SprintController.php";

        $controller = new SprintController($db);

        if (method_exists($controller, $accion)) {
            $controller->$accion();
        } else {
            echo "Acción de sprint no encontrada.";
        }

        break;
//Reemplazar "case historias" cuando el código esté listo
		case "historias":

		echo "<h2>Sección de historias de usuario</h2>";
		echo "<p>En desarrollo.</p>";
		echo '<a href="index.php">Volver al inicio</a>';

		break;

/* Código de historias...
    case "historias":

        require_once __DIR__ . "/../app/Controllers/HistoriaController.php";

        $controller = new HistoriaController($db);

        if (method_exists($controller, $accion)) {
            $controller->$accion();
        } else {
            echo "Acción de historias no encontrada.";
        }

        break;
*/

    case "informes":

        require_once __DIR__ . "/../app/Controllers/InformeController.php";

        $controller = new InformeController($db);

        if (method_exists($controller, $accion)) {
            $controller->$accion();
        } else {
            echo "Acción de informes no encontrada.";
        }

        break;

    case "inicio":
    default:

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestor de Historias de Usuario</title>

    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <header>
        <h1>Gestor de Historias de Usuario</h1>

        <p>
            Administra tus historias de usuario y organiza
            el trabajo de cada sprint.
        </p>
    </header>

    <nav>
        <ul>
            <li>
                <a href="index.php?modulo=inicio">
                    Inicio
                </a>
            </li>

            <li>
                <a href="index.php?modulo=historias">
                    Historias de usuario
                </a>
            </li>

            <li>
                <a href="index.php?modulo=sprints">
                    Sprints
                </a>
            </li>

            <li>
                <a href="index.php?modulo=informes">
                    Informes
                </a>
            </li>
        </ul>
    </nav>

    <main>

        <section>
            <h2>Bienvenido</h2>

            <p>
                Organiza el trabajo de tu equipo mediante
                historias de usuario y sprints.
            </p>
        </section>

        <section>
            <h2>Menú principal</h2>

            <article>
                <h3>Historias de usuario</h3>

                <p>
                    Crea, consulta, modifica y elimina historias
                    de usuario. Asigna responsables, puntos
                    de complejidad y estados.
                </p>

                <a href="index.php?modulo=historias">
                    Administrar historias
                </a>
            </article>

            <article>
                <h3>Sprints</h3>

                <p>
                    Organiza las historias de usuario en
                    diferentes sprints y consulta el tablero
                    de trabajo de cada uno.
                </p>

                <a href="index.php?modulo=sprints">
                    Administrar sprints
                </a>
            </article>

            <article>
                <h3>Informes</h3>

                <p>
                    Consulta el progreso general del proyecto,
                    las historias finalizadas, pendientes y
                    aquellas que presentan impedimentos.
                </p>

                <a href="index.php?modulo=informes">
                    Consultar informes
                </a>
            </article>

        </section>

    </main>

    <footer>
        <p>Gestor de Historias de Usuario - Proyecto académico</p>
    </footer>

</body>

</html>

<?php
        break;
}
?>
