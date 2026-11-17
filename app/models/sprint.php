<?php

class Sprint
{
    private $conn;
    private $tabla = "sprints";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM " . $this->tabla . " ORDER BY fecha_inicio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function crear($nombre, $fechaInicio, $fechaFin)
    {
        $sql = "INSERT INTO " . $this->tabla . " (nombre, fecha_inicio, fecha_fin)
                VALUES (:nombre, :fecha_inicio, :fecha_fin)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":fecha_inicio", $fechaInicio);
        $stmt->bindParam(":fecha_fin", $fechaFin);
        return $stmt->execute();
    }

    public function actualizar($id, $nombre, $fechaInicio, $fechaFin)
    {
        $sql = "UPDATE " . $this->tabla . "
                SET nombre = :nombre,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":fecha_inicio", $fechaInicio);
        $stmt->bindParam(":fecha_fin", $fechaFin);
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM " . $this->tabla . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerHistorias($sprintId)
    {
        $sql = "SELECT * FROM historias WHERE sprint_id = :sprint_id ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":sprint_id", $sprintId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}