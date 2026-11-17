<?php

require_once __DIR__ . "/../models/Sprint.php";

class SprintController
{
    private $sprint;

    public function __construct($db)
    {
        $this->sprint = new Sprint($db);
    }

    //Mostrar todos los sprints
    public function index()
    {
        $sprints = $this->sprint->obtenerTodos();

        require_once __DIR__ . "/../views/sprints/index.php";
    }

    //Mostrar el tablero de un sprint
    public function tablero()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: index.php?modulo=sprints&accion=index");
            exit;
        }

        $sprint = $this->sprint->obtenerPorId($id);

        if (!$sprint) {
            header("Location: index.php?modulo=sprints&accion=index");
            exit;
        }

        $historias = $this->sprint->obtenerHistorias($id);

        require_once __DIR__ . "/../views/sprints/tablero.php";
    }

    //Crear un sprint
    public function crear()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $nombre = trim($_POST["nombre"] ?? "");
            $fechaInicio = $_POST["fecha_inicio"] ?? "";
            $fechaFin = $_POST["fecha_fin"] ?? "";

            if (
                $nombre !== "" &&
                $fechaInicio !== "" &&
                $fechaFin !== "" &&
                $fechaInicio <= $fechaFin
            ) {
                $this->sprint->crear(
                    $nombre,
                    $fechaInicio,
                    $fechaFin
                );
            }
        }

        header("Location: index.php?modulo=sprints&accion=index");
        exit;
    }

    //Actualizar un sprint
    public function actualizar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = trim($_POST["nombre"] ?? "");
            $fechaInicio = $_POST["fecha_inicio"] ?? "";
            $fechaFin = $_POST["fecha_fin"] ?? "";

            if (
                $id &&
                $nombre !== "" &&
                $fechaInicio !== "" &&
                $fechaFin !== "" &&
                $fechaInicio <= $fechaFin
            ) {
                $this->sprint->actualizar(
                    $id,
                    $nombre,
                    $fechaInicio,
                    $fechaFin
                );
            }
        }

        header("Location: index.php?modulo=sprints&accion=index");
        exit;
    }

    //Eliminar un sprint
    public function eliminar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if ($id) {
                $this->sprint->eliminar($id);
            }
        }

        header("Location: index.php?modulo=sprints&accion=index");
        exit;
    }
}