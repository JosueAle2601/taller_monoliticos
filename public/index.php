<?php

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("No se pudo conectar con la base de datos.");
}

$modulo = $_GET['modulo'] ?? 'inicio';
$accion = $_GET['accion'] ?? 'index';

switch ($modulo) {

    case 'historias':
        require_once __DIR__ . '/../app/controllers/HistoriaController.php';
        $controller = new HistoriaController($db);
        $accionesPermitidas = [
            'index', 'crear', 'guardar', 'editar', 'actualizar', 'eliminar'
        ];
        break;

    case 'sprints':
        require_once __DIR__ . '/../app/controllers/SprintController.php';
        $controller = new SprintController($db);
        $accionesPermitidas = [
            'index', 'crear', 'actualizar', 'eliminar', 'tablero'
        ];
        break;

    case 'informes':
        require_once __DIR__ . '/../app/controllers/InformeController.php';
        $controller = new InformeController($db);
        $accionesPermitidas = [
            'index'
        ];
        break;

    case 'inicio':
        require_once __DIR__ . '/../app/views/inicio.php';
        exit();

    default:
        http_response_code(404);
        echo "El módulo solicitado no existe.";
        exit();
}

//Validar y ejecutar acción
if (in_array($accion, $accionesPermitidas, true)) {
    if (is_callable([$controller, $accion])) {
        $controller->$accion();
    } else {
        http_response_code(404);
        echo "La acción no está implementada en el controlador.";
    }
} else {
    http_response_code(404);
    echo "La acción solicitada no existe.";
}