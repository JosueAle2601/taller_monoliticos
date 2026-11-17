<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/historia.php';

class HistoriaController {
    private $db;
    private $historiaModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->historiaModel = new Historia($this->db);
    }

    public function index() {
        $sprint_id = isset($_GET['sprint_id']) && $_GET['sprint_id'] !== '' ? intval($_GET['sprint_id']) : null;
        
        $historias = $this->historiaModel->obtenerTodas($sprint_id);
        $sprints = $this->historiaModel->obtenerSprintsDisponibles();
        $estadisticas = $this->historiaModel->obtenerEstadisticas();

        require_once __DIR__ . '/../views/historias/index.php';
    }

    public function crear() {
        $historia = null; // No hay datos previos porque es nueva
        $sprints = $this->historiaModel->obtenerSprintsDisponibles();
        require_once __DIR__ . '/../views/historias/formulario.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->historiaModel->titulo = trim($_POST['titulo'] ?? '');
            $this->historiaModel->descripcion = trim($_POST['descripcion'] ?? '');
            $this->historiaModel->responsable = trim($_POST['responsable'] ?? '');
            $this->historiaModel->estado = $_POST['estado'] ?? 'nueva';
            $this->historiaModel->puntos = intval($_POST['puntos'] ?? 1);
            $this->historiaModel->fecha_creacion = !empty($_POST['fecha_creacion']) ? $_POST['fecha_creacion'] : date('Y-m-d');
            $this->historiaModel->fecha_finalizacion = !empty($_POST['fecha_finalizacion']) ? $_POST['fecha_finalizacion'] : null;
            $this->historiaModel->sprint_id = intval($_POST['sprint_id'] ?? 0);

            if (!empty($this->historiaModel->titulo) && !empty($this->historiaModel->responsable) && $this->historiaModel->sprint_id > 0) {
                $this->historiaModel->crear();
            }
        }
        header("Location: index.php?controller=historia&action=index");
        exit();
    }

    public function editar() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $historia = $this->historiaModel->obtenerPorId($id);

        if (!$historia) {
            header("Location: index.php?controller=historia&action=index");
            exit();
        }

        $sprints = $this->historiaModel->obtenerSprintsDisponibles();
        require_once __DIR__ . '/../views/historias/formulario.php';
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->historiaModel->id = intval($_POST['id'] ?? 0);
            $this->historiaModel->titulo = trim($_POST['titulo'] ?? '');
            $this->historiaModel->descripcion = trim($_POST['descripcion'] ?? '');
            $this->historiaModel->responsable = trim($_POST['responsable'] ?? '');
            $this->historiaModel->estado = $_POST['estado'] ?? 'nueva';
            $this->historiaModel->puntos = intval($_POST['puntos'] ?? 1);
            $this->historiaModel->fecha_creacion = !empty($_POST['fecha_creacion']) ? $_POST['fecha_creacion'] : date('Y-m-d');
            $this->historiaModel->fecha_finalizacion = !empty($_POST['fecha_finalizacion']) ? $_POST['fecha_finalizacion'] : null;
            $this->historiaModel->sprint_id = intval($_POST['sprint_id'] ?? 0);

            if ($this->historiaModel->id > 0 && !empty($this->historiaModel->titulo)) {
                $this->historiaModel->actualizar();
            }
        }
        header("Location: index.php?controller=historia&action=index");
        exit();
    }

    public function eliminar() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $this->historiaModel->eliminar($id);
        }
        header("Location: index.php?controller=historia&action=index");
        exit();
    }
}

