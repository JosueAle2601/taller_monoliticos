<?php

class Historia {
    private $conn;
    private $table_name = "historias";

    // Atributos mapeados a la tabla 'historias'
    public $id;
    public $titulo;
    public $descripcion;
    public $responsable;
    public $estado;
    public $puntos;
    public $fecha_creacion;
    public $fecha_finalizacion;
    public $sprint_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las historias junto con el nombre de su sprint
    public function obtenerTodas($sprint_id = null) {
        $query = "SELECT h.*, s.nombre AS sprint_nombre 
                  FROM " . $this->table_name . " h
                  INNER JOIN sprints s ON h.sprint_id = s.id";
        
        if (!empty($sprint_id)) {
            $query .= " WHERE h.sprint_id = :sprint_id";
        }
        
        $query .= " ORDER BY h.id DESC";

        $stmt = $this->conn->prepare($query);

        if (!empty($sprint_id)) {
            $stmt->bindParam(":sprint_id", $sprint_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener una sola historia por su ID (para editar)
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Crear una nueva historia de usuario
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (titulo, descripcion, responsable, estado, puntos, fecha_creacion, fecha_finalizacion, sprint_id) 
                  VALUES 
                  (:titulo, :descripcion, :responsable, :estado, :puntos, :fecha_creacion, :fecha_finalizacion, :sprint_id)";

        $stmt = $this->conn->prepare($query);

        // Si el estado inicial es 'finalizada' y no hay fecha, asignamos hoy
        if ($this->estado === 'finalizada' && empty($this->fecha_finalizacion)) {
            $this->fecha_finalizacion = date('Y-m-d');
        } elseif ($this->estado !== 'finalizada') {
            $this->fecha_finalizacion = null;
        }

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":responsable", $this->responsable);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":puntos", $this->puntos, PDO::PARAM_INT);
        $stmt->bindParam(":fecha_creacion", $this->fecha_creacion);
        $stmt->bindParam(":fecha_finalizacion", $this->fecha_finalizacion);
        $stmt->bindParam(":sprint_id", $this->sprint_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Actualizar una historia existente
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                  SET titulo = :titulo,
                      descripcion = :descripcion,
                      responsable = :responsable,
                      estado = :estado,
                      puntos = :puntos,
                      fecha_creacion = :fecha_creacion,
                      fecha_finalizacion = :fecha_finalizacion,
                      sprint_id = :sprint_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Control automático de fecha de finalización según estado
        if ($this->estado === 'finalizada' && empty($this->fecha_finalizacion)) {
            $this->fecha_finalizacion = date('Y-m-d');
        } elseif ($this->estado !== 'finalizada') {
            $this->fecha_finalizacion = null;
        }

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":responsable", $this->responsable);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":puntos", $this->puntos, PDO::PARAM_INT);
        $stmt->bindParam(":fecha_creacion", $this->fecha_creacion);
        $stmt->bindParam(":fecha_finalizacion", $this->fecha_finalizacion);
        $stmt->bindParam(":sprint_id", $this->sprint_id, PDO::PARAM_INT);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Eliminar una historia
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Obtener lista de sprints disponibles (para el select del formulario y filtros)
    public function obtenerSprintsDisponibles() {
        $query = "SELECT id, nombre, fecha_inicio, fecha_fin FROM sprints ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener conteos generales para las tarjetas KPI
    public function obtenerEstadisticas() {
        $query = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN estado = 'finalizada' THEN 1 ELSE 0 END) AS finalizadas,
                    SUM(CASE WHEN estado IN ('nueva', 'activa') THEN 1 ELSE 0 END) AS en_proceso,
                    SUM(CASE WHEN estado = 'impedimento' THEN 1 ELSE 0 END) AS impedimentos
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }
}